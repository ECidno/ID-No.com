<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 * /*********************************************************************/

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Twig\Environment;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * Abstract Controller
 *
 * @Route("/", name="idno_")
 */
class AbstractController extends SymfonyAbstractController
{
    /**
     * @var int curl timeout
     */
    const CURL_TIMEOUT = 6000;

    /**
     * @var array settings
     */
    protected $settings = [];

    /**
     * @var object session
     */
    protected $session = null;

    /**
     * @var string language
     */
    protected $language = 'de';

    /**
     * @var TranslatorInterface $translator
     */
    protected $translator;

    /**
     * @var LoggerInterface logger
     */
    protected $logger;

    /**
     * @var EntityManagerInterface emDefault
     */
    protected $emDefault;

    /**
     * @var EntityManagerInterface emNutzer
     */
    protected $emNutzer;

    /**
     * @var FormFactoryInterface formFactory
     */
    protected $formFactory;

    /**
     * @var array
     */
    protected $motd = [];

    /**
     * @var bool ajax
     */
    protected $ajax = false;

    /**
     * @var string controllerName
     */
    protected $controllerName = null;

    /**
     * @var string actionName
     */
    protected $actionName = null;

    /**
     * @var string template
     */
    protected $template = 'index.html.twig';

    /**
     * @var RequestStack requestStack
     */
    protected $requestStack;

    /**
     * @var RequeEnvironmentstStack twig
     */
    protected $twig;

    /**
     * @var \DateTime now
     */
    protected $now;


    /**
     * constructor
     *
     * @param ContainerBagInterface $params
     * @param Environment $twig
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @param ManagerRegistry $registry
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        ContainerBagInterface $params,
        Environment $twig,
        RequestStack $requestStack,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        ManagerRegistry $registry,
        FormFactoryInterface $formFactory
    ) {
        $this->now = new \DateTime();

        // Current route:
        //dump($requestStack->getCurrentRequest()->attributes->get('_route'));

        $this->settings = $params->get('settings');
        $this->translator = $translator;
        $this->logger = $logger;
        $this->emDefault = $registry->getManager('default');
        $this->emNutzer = $registry->getManager('nutzer');
        $this->formFactory = $formFactory;

        /*
         * request
         */
        $this->requestStack = $requestStack;
        $masterRequest = $this->requestStack->getMainRequest();
        $currentRequest = $this->requestStack->getCurrentRequest();

        /*
         * session, language, locale
         */
        $this->session = $currentRequest->getSession();
        $this->language = $currentRequest->getLocale();

        /*
         * controller, action
         */

        // assign controller and action
        if (strpos($currentRequest->attributes->get('_controller'), '::') !== false) {
            list(
              $this->controllerName,
              $this->actionName
            ) = explode('::', $currentRequest->attributes->get('_controller'));
        } else {
            list(
              $this->controllerName,
              $this->actionName
            ) = explode(':', $currentRequest->attributes->get('_controller'));
        }

        // reduce controller name
        $this->controllerName = substr(
            $this->controllerName,
            strrpos($this->controllerName, '\\') + 1,
            -10
        );

        // action: default to 'index'
        $this->actionName = empty($this->actionName)
            ? 'index'
            : $this->actionName;

        /*
         * template
         */

        // set default template name for controller/action
        $this->template = join(
            '/',
            [
                strtolower($this->controllerName),
                $this->actionName . '.html.twig',
            ]
        );

        // ajax
        $this->ajax = $currentRequest->isXmlHttpRequest();

        // motd
        $this->_getMotd();

        // Twig globals
        $this->twig = $twig;
        $this->setDefaultTwigArguments($twig, $params);
    }


    /**
     * Translate function
     *
     * @param string $id
     * @param array $arguments
     * @param mixed $qty
     *
     * @return string
     */
    protected function lll($id, $arguments = [], $qty = null): string
    {
        $domain = 'messages';
        $message = $this->translator->trans($id, $arguments, $domain);

        // return
        return $message;
    }


    /**
     * default twig arguments
     *
     * @param Environment $twig
     */
    protected function setDefaultTwigArguments(Environment $twig): void
    {
        $twig->addGlobal('settings', $this->settings);
        $twig->addGlobal('ajax', $this->ajax);
        $twig->addGlobal('language', $this->language);
        $twig->addGlobal('motd', $this->motd);
    }


    /**
     * set referrer
     *
     * @param Request $request
     */
    protected function setReferrrer(Request $request): void
    {
        // save referrer in session
        if(!$this->ajax) {
            $request->getSession()->set('referrer', $request->getUri());
        }
    }


    /**
     * Get motd
     *
     *  @param void
     *
     * @return void
     */
    protected function _getMotd(): void
    {
        $dir = __DIR__.'/../../motd';

        // exists?
        if (file_exists($dir)) {
            $finder = new Finder();
            $finder
                ->in($dir)
                ->files()
                ->name(
                    [
                        'info',
                        'primary',
                        'secondary',
                        'success',
                        'warning',
                        'danger',
                        'light',
                        'dark',
                    ]
                );

            // proceed if we have results
            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $class = strtolower(trim($file->getFileName()));
                    $text = strip_tags(trim($file->getContents()));
                }

                // motd
                $this->motd = [
                    'class' => $class,
                    'text' => $text
                ];
            }
        }
    }


    /**
     * render vars and return response
     *
     * @param array $variables
     * @param bool $forceJsonResponse
     *
     * @return Response
     */
    protected function renderAndRespond($variables = [], $forceJsonResponse = false): Response
    {
        $loader = $this->twig->getLoader();

        // check if template exists
        if (!$loader->exists($this->template)) {
            return $this->respond404();
        }

        // render
        $response = $this->render(
            $this->template,
            $variables
        );

        // return json
        if ($this->ajax === true || $forceJsonResponse === true) {
            return $this->json(
                [
                    'status' => 'success',
                    'html' => $response->getContent(),
                ]
            );

        // return render response
        } else {
            return $response;
        }
    }


    /**
     * render vars, response or callback
     *
     * @param array $variables
     * @param string $callback
     *
     * @return Response
     */
    protected function renderRespondOrCallback($variables, $callback = null): Response
    {
        // render
        $response = $this->render(
            $this->template,
            $variables
        );

        // return json
        if(!is_null($callback)) {

            // build response
            $json = $this->json(
                [
                    'status' => 'success',
                    'html' => $response
                ]
            );

            // return callback
            return "$callback($json)";

        // return response
        } else {
            return $response;
        }
    }


    /**
     * get 404 response
     *
     * @param string $message
     *
     * @return Response
     */
    protected function respond404($message = null): Response
    {
        $response = new Response(
            '<html><body><h1>Not found!</h1>'.$message.'</body></html>',
            Response::HTTP_NOT_FOUND,
            [
                'Content-Type' => 'text/html'
            ]
        );

        // return
        return $response;
    }
}
