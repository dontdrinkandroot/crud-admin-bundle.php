<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Event\PostPersistEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatableMessage;

class PostPersistFlashListener
{
    public function onPostPersist(PostPersistEvent $event): void
    {
        Asserted::instanceOf($event->request->getSession(), Session::class)->getFlashBag()->add(
            'success',
            new TranslatableMessage(
                message: 'success.' . strtolower($event->crudOperation->value),
                parameters: [
                    '%entityClass%' => $event->entityClass,
                    '%entity%' => $event->data
                ],
                domain: 'DdrCrudAdmin'
            )
        );
    }
}
