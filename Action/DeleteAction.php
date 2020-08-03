<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DeleteAction
{

    private ItemResolver $itemResolver;

    private AuthorizationCheckerInterface $authorizationChecker;

    private EventDispatcherInterface $eventDispatcher;

    private ItemPersister $itemPersister;

    public function __construct(
        ItemResolver $itemResolver,
        ItemPersister $itemPersister,
        AuthorizationCheckerInterface $authorizationChecker,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->itemResolver = $itemResolver;
        $this->authorizationChecker = $authorizationChecker;
        $this->eventDispatcher = $eventDispatcher;
        $this->itemPersister = $itemPersister;
    }

    public function __invoke(Request $request): Response
    {
        $context = new CrudAdminContext(RequestAttributes::getEntityClass($request), CrudOperation::DELETE, $request);
        $entity = $this->itemResolver->resolve($context);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$this->authorizationChecker->isGranted(CrudOperation::DELETE, $entity)) {
            throw new AccessDeniedException();
        }

        $this->itemPersister->persistItem($context);

        $response = new Response();
        $createResponseEvent = new CreateResponseEvent($context, $response);
        $this->eventDispatcher->dispatch($createResponseEvent);

        return $createResponseEvent->getResponse();
    }
}
