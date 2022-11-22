<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Main\Items;
use App\Entity\Nutzer\Nutzer;
use App\Entity\Nutzer\NutzerAuth;
use App\Entity\Nutzer\Person;
use App\Form\Type\ItemsAddType;
use App\Form\Type\ItemsEditType;
use App\Form\Type\RegistrationType;
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
     * @param string $idno
     *
     * @return Response
     *
     * @Route("/notfallpass/{idno?}", name="pass", methods={"GET", "POST"})
     */
    public function pass(Request $request, $idno = null): Response
    {
        $idno = strtoupper($request->get('p_idno') ?? $idno);

        // get item
        $item = $this->emDefault
            ->getRepository(Items::class)
            ->findOneByIdNo($idno);

        // not found
        if($item === null) {
            $this->addFlash(
                'error',
                $this->translator->trans('ID-Number not found!')
            );
            return $this->redirectToRoute('app_standard_index');
        }

        // switch item statis (noStatus)
        switch ($item->getNoStatus()) {
            case 'deaktiviert':
                $this->addFlash(
                    'error',
                    $this->translator->trans('ID-Number locked!')
                );
                return $this->redirectToRoute('app_standard_index');
                break;

            // ready for registration (activation)
            case 'aktiviert':
                return $this->redirectToRoute(
                    'app_items_register',
                    [
                        'idno' => $idno
                    ]
                );
                break;

            // active
            case 'registriert':

                // proceed
                $nutzerId = $item->getNutzerId();
                $personId = $item->getPersonId();

                // user
                $nutzer = $this->emNutzer
                    ->getRepository(Nutzer::class)
                    ->findOneById($nutzerId);
                $person = $this->emNutzer
                    ->getRepository(Person::class)
                    ->findOneById($personId);

                // user pass active (sichtbar)
                if($nutzer->getSichtbar() === false) {
                    $this->session->getFlashBag()->add(
                        'error',
                        $this->translator->trans('ID-Number locked!')
                    );
                    return $this->redirectToRoute('app_standard_index');
                }

                // variables
                $variables = [
                    'idno' => $item,
                    'nutzer' => $nutzer,
                    'person' => $person,
                ];

                // mail
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

                // return
                return $this->renderAndRespond($variables);
                break;

            // redirect to index - to be sure
            default:
                return $this->redirectToRoute('app_standard_index');
                break;
        }
    }


    /**
     * register
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordEncoder
     * @param string $idno
     *
     * @return Response
     *
     * @Route("/registrieren/{idno?}", name="register", methods={"GET", "POST"})
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder,$idno = null): Response
    {
        $now = new \DateTime();
        $idno = strtoupper($request->get('p_idno') ?? $idno);

        // get item
        $item = $this->emDefault
            ->getRepository(Items::class)
            ->findOneByIdNo($idno);

        // not found
        if($item === null) {
            $this->addFlash(
                'error',
                $this->translator->trans('ID-Number not found!')
            );
            return $this->redirectToRoute('app_standard_index');
        }

        // switch item statis (noStatus)
        switch ($item->getNoStatus()) {
            case 'deaktiviert':
                $this->addFlash(
                    'error',
                    $this->translator->trans('ID-Number locked!')
                );
                return $this->redirectToRoute('app_standard_index');
                break;

            // active
            case 'registriert':
                $this->addFlash(
                    'error',
                    $this->translator->trans('ID-Number already registered!')
                );
                return $this->redirectToRoute('app_standard_index');
                break;

            // ready for activation
            case 'aktiviert':

                $nutzer = new Nutzer();
                $form = $this->createForm(RegistrationType::class, $nutzer);
                $form
                    ->get('idno')
                    ->setData($idno);

                $form->handleRequest($request);

                // form valid
                if ($form->isSubmitted() && $form->isValid()) {

                    // item
                    # @TODO: Validation
                    $idno = $form->get('idno')->getData();
                    $item = $this->emDefault
                        ->getRepository(Items::class)
                        ->findOneByIdNo($idno);

                    # Code from old backend
                    $source = isset($_SESSION['source']) ? $_SESSION['source'] : 1;

                    // person
                    $person = new Person();
                    $person
                        ->setNutzer($nutzer)
                        ->setParentId(0)
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
                        $this->emNutzer
                            ->getRepository(NutzerAuth::class)
                            ->findOneByAuth($auth) !== null
                    );

                    $nutzerAuth = new NutzerAuth();
                    $nutzerAuth
                        ->setAuth($auth)
                        ->setTime(time())
                        ->setNutzer($nutzer);

                    // persist
                    $this->emNutzer->persist($nutzer);
                    $this->emNutzer->persist($nutzerAuth);
                    $this->emNutzer->flush();

                    // update item
                    $item
                        ->setNoStatus('registriert')
                        ->setNutzerId($nutzer->getId())
                        ->setPersonId($person->getId())
                        ->setAktiviertDatum($now);

                    // persist
                    $this->emDefault->persist($item);
                    $this->emDefault->flush();

                    // redirect to profile
                    return $this->redirectToRoute('app_profile_index');
                }


                // variables
                $variables = [
                    'item' => $item,
                    'form' => $form->createView()
                ];

                // return
                return $this->renderAndRespond($variables);
                break;

            // redirect to index - to be sure
            default:
                return $this->redirectToRoute('app_standard_index');
                break;
        }
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
        $user = $this->getUser();
        $person = $this->emNutzer
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
            ->setNutzerId($user->getId())
            ->setPersonId($person->getId());

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