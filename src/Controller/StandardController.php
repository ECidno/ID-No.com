<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 * /*********************************************************************/

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Standard controller
 *
 * @Route("/", name="app_standard_")
 */
class StandardController extends AbstractController
{
    /**
     * index action
     *
     * @param string $idno
     * @param Request $request
     *
     *
     * @return Response
     *
     * @Route("/{idno<[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}>?}", name="index", priority=100, methods={"GET", "POST"})
     */
    public function index($idno = null, Request $request): Response
    {
        $bannerImages = [];
        $dir = 'media/images/banner';
        $idno = strtoupper($request->get('p_idno') ?? $idno);

        // redirect to pass
        if(!empty($idno)) {
            return $this->redirectToRoute(
                'app_item_pass',
                [
                    'idno' => $idno,
                ]
            );
        }

        // exists?
        if (file_exists($dir)) {
            $finder = new Finder();

            // get banner images form asset path
            $finder
                ->ignoreUnreadableDirs()
                ->files()
                ->in($dir);
            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $bannerImages[] = $file->getRelativePathname();
                }
                asort($bannerImages);
            }
        }

        // variables
        $variables = [
            'banner_images' => $bannerImages,
        ];

        // return
        return $this->renderAndRespond($variables);
    }


    /**
     * content action
     *
     * @param string $slug
     *
     * @return Response
     *
     * Route("/{slug}", name="content", priority=10, methods={"GET"})
     */
    public function content($slug): Response
    {
        // set template name for controller/action
        $this->template = join(
            '/',
            [
                strtolower($this->controllerName),
                strtolower($slug).'.html.twig',
            ]
        );

        // variables
        $variables = [
            'slug' => $this->settings['pages'][$slug] ?? $slug,
        ];

        // return
        return $this->renderAndRespond($variables);
    }


    /**
     * example action
     *
     * @param Request $request
     *
     * @Route("/example", name="example", methods={"GET"})
     *
     * @return Response
     */
    public function example(): Response
    {
        // variables
        $variables = [];

        // return
        return $this->renderAndRespond($variables);
    }
}
