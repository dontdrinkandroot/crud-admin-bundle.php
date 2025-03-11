<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\RedirectAfterWriteEvent;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolverInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DefaultRedirectAfterWriteListener
{
    public function __construct(
        private readonly UrlResolverInterface $urlResolver,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function onRedirectAfterWrite(RedirectAfterWriteEvent $event): void
    {
        if (null !== $event->response) {
            return;
        }

        $redirectUrl = $this->urlResolver->resolveUrl($event->entityClass, CrudOperation::LIST, $event->entity);
        if (
            null !== $redirectUrl
            && $this->authorizationChecker->isGranted(CrudOperation::LIST->value, $event->entityClass)
        ) {
            $event->response = new RedirectResponse($redirectUrl);
        }
    }
}
