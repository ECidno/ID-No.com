<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Person;
use App\Entity\PersonImages;
use App\Form\Type\PersonType;
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
    public static $entityFormAddType = PersonType::class;

    /**
     * @var string entityFormType
     */
    public static $entityFormEditType = PersonType::class;


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
     * set up to date
     *
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Route("/up_to_date/{id}", name="up_to_date", methods={"GET"})
     */
    public function setUpToDate(int $id, Request $request): JsonResponse
    {
        $em = $this->emDefault;
        
        // person
        $person = $this->emDefault
            ->getRepository(Person::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('edit', $person);

        $person->setLastChangeDatum(new \DateTime('now'));

        $em->persist($person);
        $em->flush($person);

        $message = 'ok'; 

        // return
        return $this->json(
            [
                'message' => $message,
            ]
        );
    }
}
