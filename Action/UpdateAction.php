<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UpdateAction
{
    private ItemResolver $itemResolver;

    private FormResolver $formResolver;

    private AuthorizationCheckerInterface $authorizationChecker;

    private EventDispatcherInterface $eventDispatcher;

    private ItemPersister $itemPersister;

    public function __construct(
        ItemResolver $itemResolver,
        FormResolver $formResolver,
        ItemPersister $itemPersister,
        AuthorizationCheckerInterface $authorizationChecker,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->itemResolver = $itemResolver;
        $this->formResolver = $formResolver;
        $this->authorizationChecker = $authorizationChecker;
        $this->eventDispatcher = $eventDispatcher;
        $this->itemPersister = $itemPersister;
    }

    public function __invoke(Request $request): Response
    {
        $context = new CrudAdminContext(RequestAttributes::getEntityClass($request), CrudOperation::UPDATE, $request);
        $entity = $this->itemResolver->resolve($context);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$this->authorizationChecker->isGranted(CrudOperation::UPDATE, $entity)) {
            throw new AccessDeniedException();
        }

        $form = $this->formResolver->resolve($context);
        assert(null !== $form);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->itemPersister->persistItem($context);
        }

        $response = new Response();
        $createResponseEvent = new CreateResponseEvent($context, $response);
        $this->eventDispatcher->dispatch($createResponseEvent);

        return $createResponseEvent->getResponse();
    }
}
