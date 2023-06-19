<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Items;
use App\Entity\Person;
use App\Service\ItemsService;
use App\Service\MailService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * *** Abstract Controller
 *
 * @Route("/api/entity", name="app_api_entity_")
 */
class AbstractApiController extends SymfonyAbstractController
{
    /**
     * @var string $entityClassName
     */
    public static $entityClassName = null;

     /**
     * @var string $entityFormAddType
     */
    public static $entityFormAddType = null;

    /**
     * @var string $entityFormEditType
     */
    public static $entityFormEditType = null;

    /**
     * @var ManagerRegistry $registry
     */
    protected $registry;

    /**
     * @var EntitiyManager $em
     */
    protected $em;

    /**
     * @var EntitiyManager $emDefault
     */
    protected $emDefault;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var ItemsService $itemsService
     */
    protected $itemsService;

    /**
     * @var MailService $mailService
     */
    protected $mailService;

    /**
     * @var \DateTimeImmutable $now
     */
    protected $now;

    /**
     * @var TranslatorInterface $translator
     */
    protected $translator;

    /**
     * @var array $settings
     */
    protected $settings = [];

    /**
     * @var object $session
     */
    protected $session = null;

    /**
     * @var int $statusCode HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;


    /**
     * constructor
     *
     * @param ContainerBagInterface $params
     * @param LoggerInterface $logger
     * @param ItemsService $itemsService
     * @param MailService $mailService
     * @param ManagerRegistry $registry
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     */
    public function __construct(
        ContainerBagInterface $params,
        LoggerInterface $logger,
        ItemsService $itemsService,
        MailService $mailService,
        ManagerRegistry $registry,
        RequestStack $requestStack,
        TranslatorInterface $translator
    ) {
        $this->now = new \DateTime();
        $this->logger = $logger;
        $this->emDefault = $registry->getManager('default');
        $this->itemsService = $itemsService;
        $this->mailService = $mailService;
        $this->registry = $registry;
        $this->settings = $params->get('settings');
        $this->translator = $translator;
    }


    /**
     * Gets the value of statusCode.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }


    /**
     * Sets the value of statusCode.
     *
     * @param int $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }


    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function respond($data, $headers = []): JsonResponse
    {
        return new JsonResponse(
            $data,
            $this->getStatusCode(),
            $headers
        );
    }


    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     * @param array headers
     *
     * @return JsonResponse
     */
    public function respondWithErrors($errors, $headers = []): JsonResponse
    {
        return new JsonResponse(
            [
               'errors' => $errors,
            ],
            $this->getStatusCode(),
            $headers
        );
    }


    /**
     * create
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $object = new static::$entityClassName();
        $formType = static::$entityFormAddType;
        $em = $this->emDefault;

        // form
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);

        // form valid
        if ($form->isSubmitted() && $form->isValid()) {

            // voter
            $this->denyAccessUnlessGranted('create', $object);

            $em->persist($object);
            $em->flush($object);

            // message
            $message = $this->translator->trans(
                $this->getTranslateKey('action.create.success')
            );

        // form invalid
        } else if (!$form->isValid()) {
            $errors = $this->collectFormErrors($form);
            $message = $this->translator->trans(
                $this->getTranslateKey('action.create.error')
            );

            // Return status code 400 for validation errors: https://stackoverflow.com/a/3290198
            return $this->json(
                [
                    'message' => $message,
                    'errors' => $errors,
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        // return | page
        return $this->json(
            [
                'id' => $object->getId(),
                'message' => $message,
            ]
        );
    }


    /**
     * read
     *
     * @param int $personId
     * @param Request $request
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     *
     * @Route("/{personId}", name="read", methods={"GET", "POST"})
     */
    public function read(int $personId, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $person = $this->emDefault
            ->getRepository(Person::class)
            ->findOneBy([
                'id' => $personId,
                'nutzer' => $this->getUser(),
            ]);

        // voter check
        $this->denyAccessUnlessGranted('read', $person);

        // get
        $objects = $this->emDefault
            ->getRepository(static::$entityClassName)
            ->findByPerson($person);

        // map
        $items = $this->mapOperations($objects);

        // return
        return $this->json(
            $items,
            Response::HTTP_OK,
            [],
            ['groups' => 'read']
        );
    }


    /**
     * update
     *
     * @param int $id
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/update/{id}", name="update", methods={"POST"})
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $formType = static::$entityFormEditType;

        // get object
        $object = $this->emDefault
            ->getRepository(static::$entityClassName)
            ->find($id);

        // form
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);

        // form valid
        if ($form->isSubmitted() && $form->isValid()) {

            // voter
            $this->denyAccessUnlessGranted('update', $object);
            $this->emDefault->flush($object);

            // message
            $message = $this->translator->trans(
                $this->getTranslateKey('action.edit.success')
            );

        // form invalid
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $errors = $this->collectFormErrors($form);
            $message = $this->translator->trans(
                $this->getTranslateKey('action.edit.error')
            );

            // Return status code 400 for validation errors: https://stackoverflow.com/a/3290198
            return $this->json(
                [
                    'message' => $message,
                    'errors' => $errors,
                ],
                Response::HTTP_BAD_REQUEST
            );

        } else {
            $message = $this->translator->trans(
                $this->getTranslateKey('action.edit.error')
            );
        }

        // return
        return $this->json(
            [
                'id' => $object->getId(),
                'message' => $message,
            ]
        );
    }


    /**
     * delete
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/delete/{id}", name="delete", methods={"POST","DELETE"})
     */
    public function delete(int $id, Request $request): JsonResponse
    {
        $em = $this->emDefault;
        $object = $em
            ->getRepository(static::$entityClassName)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('delete', $object);

        // csrf
        if (
            $this->isCsrfTokenValid(
                'delete'.$object->getId(),
                $request->request->get('_token')
            )
        ) {
            $em->remove($object);
            $em->flush($object);

            // message
            $message = $this->translator->trans(
                $this->getTranslateKey('action.delete.success')
            );

         // invalid request
        } else {
            $message = $this->translator->trans(
                $this->getTranslateKey('action.delete.error')
            );

            // return
            return $this->json(
                [
                    'errors' => $message,
                ],
                Response::HTTP_PRECONDITION_FAILED
            );
        }

        // return
        return $this->json(
            [
                'message' => $message,
            ]
        );
    }


    /**
     * collect form errors
     *
     * @param FormInterface $form
     * @return array
     */
    protected function collectFormErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                $childErrors = $this->collectFormErrors($childForm);
                if (!empty($childErrors)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        // return
        return $errors;
    }


    /**
     * get translate key
     *
     * @param string $key
     * @return string
     */
    public function getTranslateKey(string $key): string
    {
        if($name = strrpos(static::$entityClassName, '\\')) {
            $name = substr(static::$entityClassName, $name + 1);
        }

        // return
        return strtolower($name.'.'.$key);
    }


    /**
     * get entity operatons
     *
     * @param iterable $objects
     * @return array
     */
    public function mapOperations($objects): iterable
    {
        return $objects;
    }
}
