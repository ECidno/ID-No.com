<?php
namespace App\Controller\IdNo;

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
 * @Route("/", name="idno_standard_")
 */
class StandardController extends AbstractController
{
    /**
     * index action
     *
     * @param Request $request
     *
     * @Route("/", name="index", methods={"GET"})
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $bannerImages = [];
        $dir = 'media/images/banner';

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
     * static action
     *
     * @param string $page
     *
     * @Route("content/{page}", name="content", methods={"GET"})
     *
     * @return Response
     */
    public function content($page): Response
    {
        // set template name for controller/action
        $this->template = join(
            '/',
            [
                strtolower($this->controllerName),
                strtolower($page) . '.html.twig',
            ]
        );

        // variables
        $variables = [
            'page' => $this->settings['pages'][$page] ?? $page,
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
