<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\NewInstance\NewInstanceResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CreateAction
{
    private FormResolver $formResolver;

    private AuthorizationCheckerInterface $authorizationChecker;

    private EventDispatcherInterface $eventDispatcher;

    private ItemPersister $itemPersister;

    private NewInstanceResolver $newInstanceResolver;

    public function __construct(
        NewInstanceResolver $newInstanceResolver,
        FormResolver $formResolver,
        ItemPersister $itemPersister,
        AuthorizationCheckerInterface $authorizationChecker,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->formResolver = $formResolver;
        $this->authorizationChecker = $authorizationChecker;
        $this->eventDispatcher = $eventDispatcher;
        $this->itemPersister = $itemPersister;
        $this->newInstanceResolver = $newInstanceResolver;
    }

    public function __invoke(Request $request): Response
    {
        $request->attributes->set(RequestAttributes::OPERATION, CrudOperation::CREATE);
        $entityClass = RequestAttributes::getEntityClass($request);
        $entity = $this->newInstanceResolver->resolve($request);
        if (!$this->authorizationChecker->isGranted(CrudOperation::CREATE, $entity)) {
            throw new AccessDeniedException();
        }

        $form = $this->formResolver->resolve($request);
        assert(null !== $form);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->itemPersister->persistItem($request);
        }

        $response = new Response();
        $createResponseEvent = new CreateResponseEvent($request, $response);
        $this->eventDispatcher->dispatch($createResponseEvent);

        return $createResponseEvent->getResponse();
    }
}
