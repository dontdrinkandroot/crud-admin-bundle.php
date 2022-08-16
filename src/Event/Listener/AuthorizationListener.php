<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\PostSetDataEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\PreSetDataEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AuthorizationListener
{
    public function __construct(private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public function onPreSetData(PreSetDataEvent $event): void
    {
        if (
            in_array($event->crudOperation, [CrudOperation::LIST, CrudOperation::CREATE], true)
            && !$this->authorizationChecker->isGranted($event->crudOperation->value, $event->entityClass)
        ) {
            throw new AccessDeniedException();
        }
    }

    public function onPostSetData(PostSetDataEvent $event): void
    {
        if (
            in_array($event->crudOperation, [CrudOperation::READ, CrudOperation::UPDATE, CrudOperation::DELETE], true)
            && !$this->authorizationChecker->isGranted($event->crudOperation->value, $event->data)
        ) {
            throw new AccessDeniedException();
        }
    }
}
