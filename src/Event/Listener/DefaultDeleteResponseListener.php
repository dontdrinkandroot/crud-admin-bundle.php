<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;

class DefaultDeleteResponseListener
{
    public function __construct(private UrlResolver $urlResolver)
    {
    }

    public function onCreateResponseEvent(CreateResponseEvent $event): void
    {
        $context = $event->context;
        $crudOperation = $context->getCrudOperation();
        if (CrudOperation::DELETE !== $crudOperation) {
            return;
        }

        $response = $event->response;
        if ($context->isItemPersisted()) {
            $redirectContext = $context->withOperation(CrudOperation::LIST);
            $redirectUrl = $this->urlResolver->resolve($redirectContext);

            if (null !== $redirectUrl) {
                $response->setStatusCode(302);
                $response->headers->set('Location', $redirectUrl);
            }
        }
    }
}
