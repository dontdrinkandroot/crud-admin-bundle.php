<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ListAction
{
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $context = new CrudAdminContext(
            Asserted::notNull(RequestAttributes::getEntityClass($request)),
            CrudOperation::LIST,
            $request
        );
        if (!$this->authorizationChecker->isGranted(CrudOperation::LIST, $context->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $response = new Response();
        $createResponseEvent = new CreateResponseEvent($context, $response);
        $this->eventDispatcher->dispatch($createResponseEvent);

        return $createResponseEvent->response;
    }
}
