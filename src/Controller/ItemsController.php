<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Items;
use App\Entity\LogEntry;
use App\Entity\Nutzer;
use App\Entity\NutzerAuth;
use App\Entity\Person;
use App\Form\Type\ItemsAddType;
use App\Form\Type\ItemsEditType;
use App\Form\Type\RegistrationType;
use App\Service\ItemsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

/**
 * items controller
 *
 * @Route("/", name="app_items_")
 */
class ItemsController extends AbstractController
{
    // entity
    public static $entityClassName = Items::class;


    /**
     * pass action
     *
     * @param Request $request
     * @param ItemsService $itemsService
     * @param ?string $idno
     * @param ?int $t
     *
     * @return Response
     *
     * @Route("/notfallpass/{idno?}/{t}", name="pass", requirements={"t"="\d+"}, methods={"GET", "POST"})
     */
    public function pass(Request $request, ItemsService $itemsService, string $idno, int $t = 0): Response
    {
        /* CNBID22-63, revert CNBID22-51
        $session = $request->getSession();

        $idNo = $request->get('p_idno')
            ?? $request
                ->getSession()
                ->get('id-no')
            ?? $idno;
        */

        $mailSent = false;
        $idNo = $request->get('p_idno') ?? $idno;
        $t = (int) ($request->get('t') ?? $t);

        /**
         * @var Nutzer
         */
        $user = $this->getUser();

        /**
         * @var Items
         */
        $item = $itemsService->check(
            $idNo,
            'itemError',
            'pass'
        );

        // redirect to index if item check failed
        if($item === null) {
            return $this->redirectToRoute('app_standard_index');

        // item not registered | ready for registration (activation)
        } elseif($item->getNoStatus() === 'aktiviert') {
            return $this->redirectToRoute(
                'app_items_register',
                [
                    'idno' => $idNo
                ]
            );

        /* CNBID22-63, revert CNBID22-51
        // idno given in path, store in session and redirect w/o number
        } elseif(!empty($idno)) {

            // store id in session and remove from path
            $session->set('id-no', $idno);

            // return
            return $this->redirectToRoute('app_items_pass');
        */

        // proceed to pass
        } else {
            $nutzer = $item->getNutzer();
            $person = $item->getPerson();

            // user pass active (sichtbar)
            if($nutzer->getSichtbar() === false) {
                $this->addFlash(
                    'itemError',
                    $this->translator->trans('ID-Number locked!')
                );

                // redirect to index
                return $this->redirectToRoute('app_standard_index');
            }

            // finally, redirect to user's locale if not match with current
            if(
                !empty($person->getSprache()) &&
                in_array(
                    $request->getLocale(),
                    [
                        'de',
                        'en',
                    ]
                ) &&
                $request->getLocale() !== $person->getSprache()
            ) {
                // return $this->redirectToRoute(
                //     'app_items_pass',
                //     [
                //         '_locale' => $person->getSprache(),
                //         'idno' => $idNo,
                //     ]
                // );
                $request->setLocale($person->getSprache());
            }

            // mail (if not user' pass is shown)

            if(
                (
                    $user === null || $user->getId() !== $nutzer->getId()
                ) && (
                    $t === 0 || ($t > 0 && $this->now->format('U') - $t < 60)
                )
            ) {
                $this->mailService->infoMail(
                    [
                        'subject' => 'Information - Ihr ID-No.com Produkt wurde genutzt!',
                        'recipientEmail' => $person->getEmail(),
                        'recipientName' => $person->getFullName(),
                        'item' => $item,
                        'nutzer' => $nutzer,
                        'person' => $person,
                        'now' => new \DateTime(),
                    ],
                    'itemScanned'
                );
                $mailSent = true;
            }

            // variables
            $variables = [
                'idno' => $idNo,
                'item' => $item,
                'nutzer' => $nutzer,
                'person' => $person,
                'mailSent' => $mailSent,
                't' => $t,
                'now' => $this->now->format('U'),
            ];

            // log
            $logEntry = new LogEntry(
                ItemsController::class,
                $item->getId(),
                'pass',
                $person->getEmail(),
                LogEntry::SEVERITY_INFO
            );

            // details
            $logEntry->setDetails([
                'ID-No.' => $idNo,
                'IP' => $request->getClientIp(),
            ]);

            // persist to database
            $this->emDefault->persist($logEntry);
            $this->emDefault->flush();

            /* CNBID22-63, revert CNBID22-51
            // remove id-no from session
            $session->set('id-no', null);
            */

            // return
            return $this->renderAndRespond($variables);
        }
    }


    /**
     * register
     *
     * @param Request $request
     * @param ItemsService $itemsService
     * @param UserPasswordHasherInterface $passwordEncoder
     * @param string $idno
     *
     * @return Response
     *
     * @Route("/registrieren/{idno<[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}>?}", name="register", methods={"GET", "POST"})
     */
    public function register(Request $request, ItemsService $itemsService, UserPasswordHasherInterface $passwordEncoder, $idno): Response
    {
        $now = new \DateTime();
        $idno = $request->get('p_idno') ?? $idno;

        // pre check idno
        if($idno !== null) {
            $item = $itemsService->check(
                $request->get('p_idno') ?? $idno,
                'itemError',
                'register'
            );

            // redirect to index if item check failed
            if($item === null) {
                return $this->redirectToRoute('app_standard_index');
            }
        }

        // proceed to registration
        $nutzer = new Nutzer();
        $form = $this->createForm(RegistrationType::class, $nutzer);
        $form
            ->get('idno')
            ->setData($idno);

        $form->handleRequest($request);

        // form valid
        if ($form->isSubmitted() && $form->isValid()) {

            // item
            $idno = $form->get('idno')->getData();
            $item = $itemsService->check(
                $idno,
                'itemError',
                'register'
            );
            if($item === null) {
                return $this->redirectToRoute('app_items_register');
            }

            // Code from old backend
            $source = isset($_SESSION['source']) ? $_SESSION['source'] : 1;

            // person
            $person = new Person();
            $person
                ->setNutzer($nutzer)
                ->setStatus('ok')
                ->setSprache('de')
                ->setEmail($nutzer->getEmail())
                ->setAnrede($nutzer->getAnrede())
                ->setVorname($nutzer->getVorname())
                ->setNachname($nutzer->getNachname());

            // nutzer (user)
            $nutzer
                ->setStatus('unlogged')
                ->setSprache('de')
                ->setLoginFehler(0)
                ->setSource($source)
                ->setPasswort(
                    $passwordEncoder->hashpassword(
                        $nutzer,
                        $nutzer->getPlainPasswort(),
                    )
                )

                // add first person (self)
                ->addPerson($person);

            // hash for email verification
            do {
                $auth = ByteString::fromRandom(40)->toString();
            } while(
                $this->emDefault
                    ->getRepository(NutzerAuth::class)
                    ->findOneByAuth($auth) !== null
            );

            // set object for mail verification
            $nutzerAuth = new NutzerAuth();
            $nutzerAuth
                ->setAuth($auth)
                ->setTime(time())
                ->setNutzer($nutzer);

            // persist
            $this->emDefault->persist($nutzer);
            $this->emDefault->persist($nutzerAuth);
            $this->emDefault->flush();

            // update item
            $item
                ->setNoStatus('registriert')
                ->setNutzer($nutzer)
                ->setPerson($person)
                ->setAktiviertDatum($now);

            // persist
            $this->emDefault->persist($item);
            $this->emDefault->flush();

            // redirect to profile
            return $this->redirectToRoute('app_profile_index');
        }

        // variables
        $variables = [
            'item' => $item ?? null,
            'form' => $form->createView()
        ];

        // return
        return $this->renderAndRespond($variables);
    }


    /**
     * new
     *
     * @param int $personId
     * @param Request $request
     * @return Response
     *
     * @Route("/items/new/{personId}", name="new", methods={"GET"})
     */
    public function new(int $personId, Request $request): Response
    {
        /**
         * @var Nutzer
         */
        $user = $this->getUser();
        $person = $this->emDefault
            ->getRepository(Person::class)
            ->findOneBy([
                'id' => $personId,
                'nutzer' => $this->getUser(),
            ]);

        // voter
        $this->denyAccessUnlessGranted('edit', $person);

        // new item
        $item = new Items();
        $item
            ->setNutzer($user)
            ->setPerson($person);

        // form
        $form = $this->formFactory->createBuilder(
            ItemsAddType::class,
            $item,
            [
                'action' => $this->generateUrl('app_api_items_create'),
            ]
        )
        ->getForm();

        // vars
        $variables = [
            'form' => $form->createView()
        ];

        // return
        return $this->renderAndRespond(
            $variables,
            true
        );
    }


    /**
     * edit
     *
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Route("/items/edit/{id}", name="edit", methods={"GET"})
     */
    public function edit(int $id, Request $request): Response
    {
        // item
        $item = $this->emDefault
            ->getRepository(Items::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('edit', $item);

        // form
        $form = $this->formFactory->createBuilder(
            ItemsEditType::class,
            $item,
            [
                'action' => $this->generateUrl(
                    'app_api_items_update',
                     [
                        'id' => $id
                    ]
                ),
            ]
        )
        ->getForm();

        // vars
        $variables = [
            'item' => $item,
            'form' => $form->createView(),
        ];

        // return
        return $this->renderAndRespond(
            $variables,
            true
        );
    }


    /**
     * delete
     *
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Route("/items/delete/{id}", name="delete", methods={"GET"})
     */
    public function delete(int $id, Request $request): Response
    {
        // item
        $item = $this->emDefault
            ->getRepository(Items::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('delete', $item);

        // form
        $form = $this
            ->createFormBuilder($item)
            ->setAction(
                $this->generateUrl(
                    'app_api_items_delete',
                    [
                        'id' => $id
                    ]
                )
            )
            ->getForm();

        // vars
        $variables = [
            'item' => $item,
            'form' => $form->createView(),
        ];

        // return
        return $this->renderAndRespond(
            $variables,
            true
        );
    }
}