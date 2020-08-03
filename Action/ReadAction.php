<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ReadAction
{
    private EventDispatcherInterface $eventDispatcher;

    private ItemResolver $itemResolver;

    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(
        ItemResolver $itemResolver,
        AuthorizationCheckerInterface $authorizationChecker,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->itemResolver = $itemResolver;
        $this->authorizationChecker = $authorizationChecker;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(Request $request): Response
    {
        $context = new CrudAdminContext(RequestAttributes::getEntityClass($request), CrudOperation::READ, $request);
        $entity = $this->itemResolver->resolve($context);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$this->authorizationChecker->isGranted(CrudOperation::READ, $entity)) {
            throw new AccessDeniedException();
        }

        $response = new Response();
        $createResponseEvent = new CreateResponseEvent($context, $response);
        $this->eventDispatcher->dispatch($createResponseEvent);

        return $createResponseEvent->getResponse();
    }
}
