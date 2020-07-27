<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
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

    public function __construct(
        FormResolver $formResolver,
        ItemPersister $itemPersister,
        AuthorizationCheckerInterface $authorizationChecker,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->formResolver = $formResolver;
        $this->authorizationChecker = $authorizationChecker;
        $this->eventDispatcher = $eventDispatcher;
        $this->itemPersister = $itemPersister;
    }

    public function __invoke(Request $request): Response
    {
        $request->attributes->set(RequestAttributes::OPERATION, CrudOperation::CREATE);
        $entityClass = RequestAttributes::getEntityClass($request);
        if (!$this->authorizationChecker->isGranted(CrudOperation::CREATE, $entityClass)) {
            throw new AccessDeniedException();
        }
        RequestAttributes::setData($request, new $entityClass());

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
