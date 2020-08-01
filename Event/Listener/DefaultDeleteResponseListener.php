<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultDeleteResponseListener
{
    private RoutesResolver $routesResolver;

    private RouterInterface $router;

    /**
     * @var UrlResolver
     */
    private UrlResolver $urlResolver;

    public function __construct(RouterInterface $router, UrlResolver $urlResolver)
    {
        $this->router = $router;
        $this->urlResolver = $urlResolver;
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $request = $event->getRequest();
        $crudOperation = $request->get(RequestAttributes::OPERATION);
        if (CrudOperation::DELETE !== $crudOperation) {
            return;
        }

        $response = $event->getResponse();
        if (true === RequestAttributes::getPersistSuccess($request)) {

            $redirectUrl = $this->urlResolver->resolve(
                RequestAttributes::getEntityClass($request),
                CrudOperation::LIST,
                $request
            );

            if (null !== $redirectUrl) {
                $response->setStatusCode(302);
                $response->headers->set('Location', $redirectUrl);
            }
        }
    }
}
