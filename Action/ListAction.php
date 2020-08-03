<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ListAction
{
    private EventDispatcherInterface $eventDispatcher;

    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function __invoke(Request $request): Response
    {
        $context = new CrudAdminContext(RequestAttributes::getEntityClass($request), CrudOperation::LIST, $request);
        if (!$this->authorizationChecker->isGranted(CrudOperation::LIST, $context->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $response = new Response();
        $createResponseEvent = new CreateResponseEvent($context, $response);
        $this->eventDispatcher->dispatch($createResponseEvent);

        return $createResponseEvent->getResponse();
    }
}
