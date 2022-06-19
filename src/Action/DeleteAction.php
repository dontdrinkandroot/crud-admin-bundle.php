<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DeleteAction
{
    public function __construct(
        private ItemResolver $itemResolver,
        private ItemPersister $itemPersister,
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventDispatcherInterface $eventDispatcher
    ) {
        $this->itemPersister = $itemPersister;
    }

    public function __invoke(Request $request): Response
    {
        $context = new CrudAdminContext(
            Asserted::notNull(RequestAttributes::getEntityClass($request)),
            CrudOperation::DELETE,
            $request
        );
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

        return $createResponseEvent->response;
    }
}
