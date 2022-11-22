<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 * /*********************************************************************/

use App\Entity\Nutzer\Person;
use App\Entity\Nutzer\NutzerAuth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * profile controller
 */
class ProfileController extends AbstractController
{
    /**
     * index action
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/meinidno", name="app_profile_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // objects
        $persons = $user->getPersons();
        $person = $persons->first() ?? [];

        // vars
        $variables = [
            'user' => $user,
            'person' => $person,
        ];
/*
        // get auth code object
        $nutzerAuth = $this->emNutzer
            ->getRepository(NutzerAuth::class)
            ->findOneByNutzer($user);

        // mail for email verification
        $this->mailService->infoMail(
            [
                'subject' => $this->translator->trans('mail.mailVerification.subject'),
                'recipientEmail' => $user->getEmail(),
                'recipientName' => $user->getFullName(),
                'nutzer' => $user,
                'nutzerAuth' => $nutzerAuth,
            ],
            'mailVerification'
        );
*/

        // return
        return $this->renderAndRespond($variables);
    }


    /**
    * edit action
    *
    * @param Request $request
    * @return Response
    *
    * @Route("/profil/edit", name="app_profile_edit", methods={"GET"})
    */
   public function edit(Request $request): Response
   {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // objects
        $persons = $user->getPersons();
        $person = $persons->first() ?? new Person();

        // form
        $form = $this->createFormBuilder($person);

        // vars
        $variables = [
            'user' => $user,
            'person' => $person,
            'form' => $form->getForm(),
        ];

        // return
        return $this->renderForm(
            $this->template,
            $variables
        );

       #return $this->renderAndRespond($variables);
   }


    /**
     * update action
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/profil/update", name="app_profile_update", methods={"POST"})
     */
    public function update(Request $request): Response
    {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // vars
        $variables = [
            'user' => $this->getUser(),
        ];

        // return
        return $this->renderAndRespond($variables);
    }


    /**
     * delete action
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/profil/delete", name="app_profile_delete", methods={"GET"})
     */
    public function delete(Request $request): Response
    {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // vars
        $variables = [
            'user' => $this->getUser(),
        ];

        // return
        return $this->renderAndRespond($variables);
    }
}
