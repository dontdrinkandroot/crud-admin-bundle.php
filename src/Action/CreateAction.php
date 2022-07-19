<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\PostProcessFormEvent;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CreateAction
{
    public function __construct(
        private readonly FormResolver $formResolver,
        private readonly ItemPersister $itemPersister,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $entityClass = Asserted::notNull(RequestAttributes::getEntityClass($request));
        $context = new CrudAdminContext(
            $entityClass,
            CrudOperation::CREATE,
            $request
        );
        if (!$this->authorizationChecker->isGranted($context->getCrudOperation(), $context->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $form = Asserted::notNull($this->formResolver->resolve($context));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = Asserted::instanceOf($form->getData(), $entityClass);
            $context->setEntity($entity);
            $this->itemPersister->persistItem($context);
            $this->eventDispatcher->dispatch(new PostProcessFormEvent($context, $form));
        }

        $response = new Response();
        $createResponseEvent = new CreateResponseEvent($context, $response);
        $this->eventDispatcher->dispatch($createResponseEvent);

        return $createResponseEvent->response;
    }
}
