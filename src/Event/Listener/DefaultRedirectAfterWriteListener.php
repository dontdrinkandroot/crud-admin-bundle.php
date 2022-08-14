<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\RedirectAfterWriteEvent;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultRedirectAfterWriteListener
{
    public function __construct(
        private readonly UrlResolver $urlResolver,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function onRedirectAfterWrite(RedirectAfterWriteEvent $event): void
    {
        Asserted::instanceOf($event->request->getSession(), Session::class)->getFlashBag()->set(
            'success',
            $this->translator->trans(id: 'success.' . strtolower($event->crudOperation->value), domain: 'DdrCrudAdmin')
        );

        $redirectUrl = $this->urlResolver->resolveUrl($event->entityClass, CrudOperation::LIST, $event->entity);
        if (
            null !== $redirectUrl
            && $this->authorizationChecker->isGranted(CrudOperation::LIST->value, $event->entityClass)
        ) {
            $event->response = new RedirectResponse($redirectUrl);
        }
    }
}
