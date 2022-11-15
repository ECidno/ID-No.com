<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Person;
use App\Entity\Nutzer\PersonImages;
use App\Form\Type\PersonType;
use DateTime;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $em = static::$entityClassName === Items::class
            ? $this->emDefault
            : $this->emNutzer;

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
            $em->flush($object);

            $personImage = $form->get('personimage')->getData();

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

                /**
                 * @var PersonImages $image
                 */
                $image = $em
                    ->getRepository(PersonImages::class)
                    ->findBy(['person' => $id]);

                if (!$image) {
                    $image = new PersonImages;
                } else {
                    $image = $image[0];
                    $oldImagePath = $this->getParameter('userimages_directory').'/'.$image->getBild();
                    $filesystem = new Filesystem();
                    if ($filesystem->exists($oldImagePath)) {
                        $filesystem->remove($oldImagePath);
                    }
                }


                $image->setPerson($object);
                $image->setStatus('ok');
                $image->setBild($filename);
                $image->setBildShow(0);
                $image->setHeight($height);
                $image->setWidth($width);
                $image->setIp($_SERVER['REMOTE_ADDR']);
                $image->setCreated(new DateTime());

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
                400
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
                'redirect-url' => '', # @TODO: entiy index route
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
        $em = static::$entityClassName === Items::class
            ? $this->emDefault
            : $this->emNutzer;
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
                412
            );
        }

        // return
        return $this->json(
            [
                'message' => $message,
                'redirect-url' => '', # @TODO: entiy index route
            ]
        );
    }
}
