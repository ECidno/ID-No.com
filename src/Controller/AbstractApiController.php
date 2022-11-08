<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * *** Abstract Controller
 *
 * @Route("/api/entity", name="app_api_entity_")
 */
class AbstractApiController extends SymfonyAbstractController
{
    /**
     * @var string entityClassName
     */
    public static $entityClassName = null;

     /**
     * @var string entityFormAddType
     */
    public static $entityFormAddType = null;

    /**
     * @var string entityFormEditType
     */
    public static $entityFormEditType = null;

    /**
     * @var string entityManager
     */
    public static $entityManager = 'nutzer';

    /**
     * @var ManagerRegistry registry
     */
    protected $registry;

    /**
     * @var EntitiyManager em
     */
    protected $em;

    /**
     * @var TranslatorInterface $translator
     */
    protected $translator;

    /**
     * @var LoggerInterface logger
     */
    protected $logger;

    /**
     * @var array settings
     */
    protected $settings = [];

    /**
     * @var object session
     */
    protected $session = null;

    /**
     * @var int HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;


    /**
     * constructor
     *
     * @param RequestStack $requestStack
     */
    public function __construct(
        ContainerBagInterface $params,
        RequestStack $requestStack,
        ManagerRegistry $registry,
        TranslatorInterface $translator,
        LoggerInterface $logger
    ) {
        $this->now = new \DateTime();
        $this->settings = $params->get('settings');
        $this->registry = $registry;

        $this->em = $registry->getManager(static::$entityManager);
        $this->translator = $translator;
        $this->logger = $logger;
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
     * read
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/", name="read", methods={"GET", "POST"})
     */
    public function read(Request $request): JsonResponse
    {
        // repository
        $repository = $this->em
            ->getRepository(static::$entityClassName);

        $query = $repository->createQueryBuilder();

        $items = $repository->findWithQuery($query);
        $totalRows = $repository->getTotalResultsForQuery($query);

        // return
        return (new JsonResponse())
            ->setStatusCode(200)
            ->setData(
                [
                    'items' => $items,
                    'count' => $totalRows,
                ]
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

        // form
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);

        // form valid
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($object);
            $this->em->flush($object);

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
            return (new JsonResponse())
                ->setStatusCode(400)
                ->setData(
                    [
                        'message' => $message,
                        'errors' => $errors,
                    ]
                );
        }

        // return | page
        return (new JsonResponse())
            ->setStatusCode(200)
            ->setData([
                'id' => $object->getId(),
                'message' => $message,
                'redirect-url' => '', # @TODO: entiy index route
            ]);
    }


    /**
     * update
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     *
     * @Route("/update/{id}", name="update", methods={"POST"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $formType = static::$entityFormEditType;

// @TODO Check id and relation!!!


        $object = $this->em
            ->getRepository(static::$entityClassName)
            ->find($id);

        // form
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);

        // form valid
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush($object);

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
            return (new JsonResponse())
                ->setStatusCode(400)
                ->setData(
                    [
                        'message' => $message,
                        'errors' => $errors,
                    ]
                );
        } else {
            $message = $this->translator->trans(
                $this->getTranslateKey('action.edit.error')
            );
        }

        // return
        return (new JsonResponse())
            ->setStatusCode(200)
            ->setData(
                [
                    'id' => $object->getId(),
                    'message' => $message,
                    'redirect-url' => '', # @TODO: entiy index route
                ]
            );
    }


    /**
     * delete
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     *
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        $object = $this->em
            ->getRepository(static::$entityClassName)
            ->find($id);

        if (
            $this->isCsrfTokenValid(
                'delete'.$object->getId(),
                $request->request->get('_token')
            )
        ) {
            $this->em->remove($object);
            $this->em->flush($object);

            $message = $this->translator->trans(
                strtolower(static::$entityClassName).'.action.delete.success'
            );

         // invalid request
        } else {
            $message = $this->translator->trans(
                strtolower(static::$entityClassName).'.action.delete.error'
            );

            // return
            return (new JsonResponse())
                ->setStatusCode(400)
                ->setData(
                    [
                        'errors' => $message,
                    ]
                );
        }

        // return
        return (new JsonResponse())
            ->setStatusCode(200)
            ->setData(
                [
                    'message' => $message,
                    'redirect-url' => '', # @TODO: entiy index route
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
}
