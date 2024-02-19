<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Items;
use App\Entity\Person;
use App\Entity\PersonImages;
use App\Form\Type\PersonType;
use App\Form\Type\PersonAddType;
use App\Service\FileUploader;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Routing\Annotation\Route;

/**
 * person api controller
 * @Route("/api/person", name="app_api_person_")
 */
class PersonApiController extends AbstractApiController
{
    /**
     * @var string entityClassName
     */
    public static $entityClassName = Person::class;

    /**
     * @var string entityFormType
     */
    public static $entityFormAddType = PersonAddType::class;

    /**
     * @var string entityFormType
     */
    public static $entityFormEditType = PersonType::class;


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

        // first "new" person created | redirect
        if($object->getNutzer()->getPersons()->count() === 2) {

            // $this->addFlash(
            //     'success',
            //     $message
            // );

            // return
            return $this->json(
                [
                    'id' => $object->getId(),
                    'redirect' => $this->generateUrl('app_profile_index'),
                ]
            );

        // return
        } else {
            return $this->json(
                [
                    'id' => $object->getId(),
                    'message' => $message,
                ]
            );
        }
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
        $em = $this->emDefault;

        // get object
        $object = $em
            ->getRepository(static::$entityClassName)
            ->find($id);

        // form
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);

        // form valid
        if ($form->isSubmitted() && $form->isValid()) {

            // voter
            $this->denyAccessUnlessGranted('update', $object);
            $em->flush();

            $personImage = null; #$form->get('personImage')->getData();
            $imageShow = $form->get('imageShow')->getData();

            /**
             * @var PersonImages $image
             */
            $image = $em
                ->getRepository(PersonImages::class)
                ->findBy(['person' => $id]);

            // image?
            if ($image) {
                $image = $image[0];
                $image->setBildShow($imageShow);
                $em->persist($image);
                $em->flush($image);
            }

            // person image
            if ($personImage) {
                $filename = uniqid().'.'.$personImage->guessExtension();
                $personImage->move($this->getParameter('userimages_directory'), $filename);
                $filepath = $this->getParameter('userimages_directory').'/'.$filename;

                list($orig_width, $orig_height) = getimagesize($filepath);

                if ($orig_width > 190) {
                    $faktor = $orig_width / 190;
                    $height = $orig_height / $faktor;
                    $width = $orig_width / $faktor;
                } else {
                    $height = $orig_height;
                    $width = $orig_width;
                }

                if ($width && ($orig_width < $orig_height)) {
                    $width = ($height / $orig_height) * $orig_width;
                } else {
                    $height = ($width / $orig_width) * $orig_height;
                }

                $imagine = new Imagine;
                $photo = $imagine->open($filepath);
                $photo->resize(new Box($width, $height))->save($filepath);

                if (!$image) {
                    $image = new PersonImages;
                } else {
                    $oldImagePath = $this->getParameter('userimages_directory').'/'.$image->getBild();
                    $filesystem = new Filesystem();
                    if ($filesystem->exists($oldImagePath)) {
                        $filesystem->remove($oldImagePath);
                    }
                }

                // set image properties
                $image
                    ->setPerson($object)
                    ->setStatus('ok')
                    ->setBild($filename)
                    ->setBildShow($imageShow)
                    ->setHeight($height)
                    ->setWidth($width)
                    ->setIp($_SERVER['REMOTE_ADDR'])
                    ->setCreated(new \DateTime());

                $em->persist($image);
                $em->flush($image);
            }

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

        /**
         * @var Nutzer
         */
        $user = $this->getUser();

        // object
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
            /**
             * @var PersonImages $image
             */
            $image = $em
                ->getRepository(PersonImages::class)
                ->findBy(['person' => $id]);

            if ($image) {
                $image = $image[0];
                $imagePath = $this->getParameter('userimages_directory').'/'.$image->getBild();
                $filesystem = new Filesystem();
                if ($filesystem->exists($imagePath)) {
                    $filesystem->remove($imagePath);
                }
                $em->remove($image);
            }

            // new person for exiting items
            $itemTargetPerson = $user->getPersons()->first();
            while($itemTargetPerson->getId() === $object->getId()) {
                $itemTargetPerson = $user->getPersons()->next();
            }

            // get id number if assigned, disable and assing them to main profile
            $items = $em
                ->getRepository(Items::class)
                ->findBy([
                    'person' => $object,
                    'nutzer' => $user,
                ]);

            // iterate items
            foreach ($items as $item) {
                $item
                    ->setPerson($itemTargetPerson)
                    ->setStatus(false);

                // update
                $em->persist($item);
                $em->flush($item);
            }

            // remove person
            $em->remove($object);
            $em->flush($object);

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
     * enable
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/enable/{id}", name="enable", methods={"GET"})
     */
    public function enable(int $id, Request $request): JsonResponse
    {
        $em = $this->emDefault;
        $object = $em
            ->getRepository(static::$entityClassName)
            ->find($id);

        // object?
        if($object === null) {
            return $this->json(
                [
                    'message' => $this->translator->trans(
                        $this->getTranslateKey('action.failure.no_object')
                    ),
                ]
            );
        }

        // voter
        $this->denyAccessUnlessGranted('enable', $object);

        $object->setStatus('ok');
        $em->persist($object);
        $em->flush();

        // return
        return $this->json(
            [
                'message' => $this->translator->trans(
                    $this->getTranslateKey('action.enable.success')
                ),
            ]
        );
    }


    /**
     * disable
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/disable/{id}", name="disable", methods={"GET"})
     */
    public function disable(int $id, Request $request): JsonResponse
    {
        $em = $this->emDefault;
        $object = $em
            ->getRepository(static::$entityClassName)
            ->find($id);

        // object?
        if($object === null) {
            return $this->json(
                [
                    'message' => $this->translator->trans(
                        $this->getTranslateKey('action.failure.no_object')
                    ),
                ]
            );
        }

        // voter
        $this->denyAccessUnlessGranted('disable', $object);

        $object->setStatus('disabled');
        $em->persist($object);
        $em->flush();

        // return
        return $this->json(
            [
                'message' => $this->translator->trans(
                    $this->getTranslateKey('action.disable.success')
                ),
            ]
        );
    }


    /**
     * upload
     *
     * @param int $id
     * @param Request $request
     * @param FileUploader $fileUploader
     *
     * @return JsonResponse
     *
     * @Route("/upload/{id}", name="upload", methods={"POST"})
     */
    public function upload(int $id, Request $request, FileUploader $fileUploader): JsonResponse
    {
        // person
        $person = $this->emDefault
            ->getRepository(Person::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('upload', $person);

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $imageShow = true;
        $imageSrc = null;

        // file?
        if($uploadedFile === null) {
            return $this->json(
                [
                    'message' => $this->translator->trans(
                        $this->getTranslateKey('action.upload.error.noFileReceived')
                    ),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        // filename, message
        $result = $fileUploader->upload($uploadedFile);
        $message = $result['status'] === Response::HTTP_OK
            ? $this->translator->trans(
                $this->getTranslateKey('action.upload.success')
            )
            : $result['message'];

        // save image
        if ($result['status'] === Response::HTTP_OK) {
            $em = $this->emDefault;
            $imgFile = $this->getParameter('userimages_directory').'/'.$result['filename'];

            /**
             * @var PersonImages $image
             */
            $image = $em
                ->getRepository(PersonImages::class)
                ->findBy(['person' => $id]);

            // image?
            if ($image) {
                $image = $image[0];
                $image->setBildShow($imageShow);
                $em->persist($image);
                $em->flush($image);
            }


            list($orig_width, $orig_height) = getimagesize($imgFile);

            if ($orig_width > 190) {
                $faktor = $orig_width / 190;
                $height = $orig_height / $faktor;
                $width = $orig_width / $faktor;
            } else {
                $height = $orig_height;
                $width = $orig_width;
            }

            if ($width && ($orig_width < $orig_height)) {
                $width = ($height / $orig_height) * $orig_width;
            } else {
                $height = ($width / $orig_width) * $orig_height;
            }

            $imagine = new Imagine;
            $photo = $imagine->open($imgFile);
            $photo->resize(new Box($width, $height))->save($imgFile);

            if (!$image) {
                $image = new PersonImages;
            } else {
                $oldImagePath = $this->getParameter('userimages_directory').'/'.$image->getBild();
                $filesystem = new Filesystem();
                if ($filesystem->exists($oldImagePath)) {
                    $filesystem->remove($oldImagePath);
                }
            }

            // set image properties
            $image
                ->setPerson($person)
                ->setStatus('ok')
                ->setBild($result['filename'])
                ->setBildShow($imageShow)
                ->setHeight($height)
                ->setWidth($width)
                ->setIp($_SERVER['REMOTE_ADDR'])
                ->setCreated(new \DateTime());

            // persist
            $em->persist($image);
            $em->flush($image);

            // mime type, image src
            $mimeTypes = new MimeTypes();
            $imgMimeType = $mimeTypes->guessMimeType($imgFile);
            $imageSrc = join(
                '',
                [
                    'data:',
                    $imgMimeType,
                    ';base64,',
                    base64_encode(
                        file_get_contents($imgFile)
                    )
                ]
            );
        }

        // return
        return $this->json(
            [
                'message' => $message,
                'filename' => $result['filename']
                    ? $result['filename']
                    : null,
                'imageSrc' => $imageSrc,
            ],
            $result['status']
        );
    }


    /**
     * uptodate
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/uptodate/{id}", name="uptodate", methods={"GET"})
     */
    public function uptodate(int $id, Request $request): JsonResponse
    {
        $em = $this->emDefault;
        $object = $em
            ->getRepository(static::$entityClassName)
            ->find($id);

        // object?
        if($object === null) {
            return $this->json(
                [
                    'message' => $this->translator->trans(
                        $this->getTranslateKey('action.failure.no_object')
                    ),
                ]
            );
        }

        // voter
        $this->denyAccessUnlessGranted('uptodate', $object);

        $object->setLastChangeDatum(new \DateTime());
        $em->persist($object);
        $em->flush();

        // return
        return $this->json(
            [
                'message' => $this->translator->trans(
                    $this->getTranslateKey('action.uptodate.success')
                ),
            ]
        );
    }


    /**
     * get entity operatons
     *
     * @param iterable $objects
     * @return array
     */
    public function mapOperations($objects): iterable
    {
        $items = [];

        // iterate
        foreach ($objects as $item) {

            // voter check | read
            $this->denyAccessUnlessGranted('read', $item);

            // set operations
            $item->setOperations(
                [
/* not jet implemented
                    'edit' => [
                        'icon' => $this->settings['buttons']['edit'],
                        'uri' => $this->generateUrl(
                            'app_person_edit',
                            [
                                'id' => $item->getId(),
                            ]
                        )
                    ],

                    'delete' => [
                        'icon' => $this->settings['buttons']['delete'],
                        'uri' => $this->generateUrl(
                            'app_person_delete',
                            [
                                'id' => $item->getId(),
                            ]
                        )
                    ],
*/
                ]
            );

            // add
            $items[] = $item;
        }

        // return
        return $items;
    }
}
