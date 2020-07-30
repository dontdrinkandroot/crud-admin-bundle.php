<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultDeleteResponseListener
{
    private RoutesResolver $routesResolver;

    private RouterInterface $router;

    public function __construct(RoutesResolver $routesResolver, RouterInterface $router)
    {
        $this->routesResolver = $routesResolver;
        $this->router = $router;
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $request = $event->getRequest();
        $crudOperation = $request->get(RequestAttributes::OPERATION);
        if (CrudOperation::DELETE !== $crudOperation) {
            return;
        }

        $response = $event->getResponse();
        $routes = $this->routesResolver->resolve($request);
        if (true === RequestAttributes::getPersistSuccess($request)) {

            if (array_key_exists(CrudOperation::LIST, $routes)) {
                $url = $this->router->generate($routes[CrudOperation::LIST]);
                $response->setStatusCode(302);
                $response->headers->set('Location', $url);
            }
        }
    }
}
