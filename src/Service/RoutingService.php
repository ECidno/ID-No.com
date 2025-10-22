<?php
namespace App\Service;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 ***********************************************************************/

use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * RoutingService
 */
class RoutingService implements RouteLoaderInterface
{
    /**
     * @var array settings
     */
    protected $settings = [];

    /**
     * @var TranslatorInterface $translator
     */
    protected $translator;


    /**
     * constructor
     *
     * @param ContainerBagInterface $params
     * @param TranslatorInterface $translator
     */
    public function __construct(
        ContainerBagInterface $params,
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
        $this->settings = $params->get('settings');
    }


    /**
     * __invoke (load routes)
     *
     * @return RouteCollection
     */
    public function __invoke(): RouteCollection
    {
        $routes = new RouteCollection();

        // iterate pages
        foreach ($this->settings['pages'] as $page) {
            $slug = $page['slug'];

            // route
            $route = new Route(
                '/'.$slug,
                [
                    '_controller' => 'App\Controller\StandardController::content',
                    'slug' => $slug
                ],
                []
            );

            // add route
            $routes->add(
                'idno_content_'.$slug,
                $route
            );
        }

        // return
        return $routes;
    }
}