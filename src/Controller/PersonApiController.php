<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Person;
use App\Form\Type\PersonType;
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
}
