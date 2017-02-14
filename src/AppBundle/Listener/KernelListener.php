<?php
namespace AppBundle\Listener;


use AppBundle\Common\RouteTrait;
use AppBundle\Common\SecurityTrait;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Kernel;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KernelListener implements EventSubscriberInterface
{
    use ContainerAwareTrait;
    use RouteTrait;
    use SecurityTrait;

    private $secureRoutes = false;
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST    => [['onRequest']],
            KernelEvents::CONTROLLER => [['onAction']],
            KernelEvents::VIEW       => [['onResponder']],
            KernelEvents::EXCEPTION  => [['onException']],
            KernelEvents::RESPONSE   => [['onResponseP3P']],
        ];
    }
    public function __construct($secureRoutes)
    {
        $this->secureRoutes = $secureRoutes;
    }
    /* ===================================================
     * Implements _role processing
     * Implements mandatory project_person_register
     */
    public function onRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) return;

        // Disable the listener in case of problems
        if (!$this->secureRoutes) {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if ($token === null) {
            return; // need this for debug bar profile nonsense
        };

        $request = $event->getRequest();
        $role = $request->attributes->get('_role');

        if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($role) {
                $event->setResponse($this->redirectToRoute('app_welcome'));
                $event->stopPropagation();
                return;
            }
            return;
        }
        if ($role && !$this->authorizationChecker->isGranted($role)) {
            $event->setResponse($this->redirectToRoute('app_welcome'));
            $event->stopPropagation();
            return;
        }
        // Make sure register is called at least once
        $user = $token->getUser();
        if ($user['registered'] !== null) {
            return;
        }
        // Allow this one through
        if ($request->attributes->get('_route') === 'project_person_register') {
            return;
        }
        $event->setResponse($this->redirectToRoute('project_person_register'));
        $event->stopPropagation();
        return;
    }
    public function onAction(/** @noinspection PhpUnusedParameterInspection */
        FilterControllerEvent $event)
    {
        return;
    }

    /* =================================================================
     * Creates and renders a view
     */
    public function onResponder(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();

        $responderAttrName = '_responder';
        if ($request->attributes->has('_format'))
        {
            $responderAttrName .= '_' . $request->attributes->get('_format');
        }
        if (!$request->attributes->has($responderAttrName)) return;

        $responderServiceId = $request->attributes->get($responderAttrName);

        /** @var Callable $responder */
        $responder = $this->container->get($responderServiceId);

        $response = $responder($request);

        $event->setResponse($response);
    }

    /* ==========================================
     * Need my own exception handler since the default one relies on twig
     *
     */
    public function onException(GetResponseForExceptionEvent $event)
    {
        /** @var Kernel $kernel */
        // $kernel = $event->getKernel(); // Mystery, getEnv is undefined here
        $kernel = $this->container->get('kernel');
        $env = $kernel->getEnvironment();
        if ($env !== 'prod') {
            return;
        }

        // Copied from Symfony KernelEventListener
        $exception = $event->getException();
        $this->logException($exception, sprintf('UNCAUGHT PHP Exception %s: "%s" at %s line %s',
            get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine()
        ));

        // Just redirect to home, no real need for a fail whale
        $response = $this->redirectToRoute('app_welcome');

        $event->setResponse($response);
    }
    private function logException(\Exception $exception, $message)
    {
        $logger = $this->container->get('logger');

        // Copied from Symfony KernelExceptionListener
        if (!$exception instanceof HttpExceptionInterface || $exception->getStatusCode() >= 500) {
            $logger->critical($message, array('exception' => $exception));
        } else {
            $logger->error($message, array('exception' => $exception));
        }
    }
    // Needed for iframes in some browsers
    public function onResponseP3P(FilterResponseEvent $event)
    {
        // P3P Policy
        $event->getResponse()->headers->set('P3P',
            'CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
    }
}