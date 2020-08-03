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
    private UrlResolver $urlResolver;

    public function __construct(UrlResolver $urlResolver)
    {
        $this->urlResolver = $urlResolver;
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $context = $event->getContext();
        $crudOperation = $context->getCrudOperation();
        if (CrudOperation::DELETE !== $crudOperation) {
            return;
        }

        $response = $event->getResponse();
        if ($context->isItemPersisted()) {

            $redirectContext = $context->recreateWithOperation(CrudOperation::LIST);
            $redirectUrl = $this->urlResolver->resolve($redirectContext);

            if (null !== $redirectUrl) {
                $response->setStatusCode(302);
                $response->headers->set('Location', $redirectUrl);
            }
        }
    }
}
