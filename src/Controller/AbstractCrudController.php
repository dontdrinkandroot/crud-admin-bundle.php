<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\PostPersistEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\PostSetDataEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\PrePersistEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\PreSetDataEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\RedirectAfterWriteEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\ViewModelEvent;
use Dontdrinkandroot\CrudAdminBundle\Exception\AbortWithResponseException;
use Dontdrinkandroot\CrudAdminBundle\Exception\EntityNotFoundException;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersisterInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolverInterface;
use Override;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Environment;

/**
 * @template T of object
 *
 * @implements CrudControllerInterface<T>
 */
abstract class AbstractCrudController implements CrudControllerInterface, ServiceSubscriberInterface
{
    final public const string NEW_ID = '__NEW__';

    protected ?ContainerInterface $container = null;

    #[Override]
    public function listAction(Request $request): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::LIST;

        $event = new PreSetDataEvent($entityClass, $crudOperation, $request);
        try {
            $this->getEventDispatcher()->dispatch($event);
        } catch (AbortWithResponseException $e) {
            return $e->response;
        }

        $pagination = Asserted::notNull(
            $this->getPaginationResolver()->resolvePagination($entityClass),
            sprintf('Could not resolve pagination for %s:%s', $entityClass, $crudOperation->value)
        );

        $event = new PostSetDataEvent($entityClass, $crudOperation, $request, $pagination);
        try {
            $this->getEventDispatcher()->dispatch($event);
        } catch (AbortWithResponseException $e) {
            return $e->response;
        }

        $template = $this->fetchTemplate($entityClass, $crudOperation);

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass' => $entityClass,
            'entities' => $pagination,
        ];
        $event = new ViewModelEvent($entityClass, $crudOperation, $context, $request);
        $this->getEventDispatcher()->dispatch($event);

        return $this->render($template, $event->context);
    }

    #[Override]
    public function readAction(Request $request, mixed $id): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::READ;

        $event = new PreSetDataEvent($entityClass, $crudOperation, $request);
        try {
            $this->getEventDispatcher()->dispatch($event);
        } catch (AbortWithResponseException $e) {
            return $e->response;
        }

        try {
            $entity = $this->getItemResolver()->resolveItem($entityClass, $crudOperation, $id);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException();
        }

        $event = new PostSetDataEvent($entityClass, $crudOperation, $request, $entity);
        try {
            $this->getEventDispatcher()->dispatch($event);
        } catch (AbortWithResponseException $e) {
            return $e->response;
        }

        $template = $this->fetchTemplate($entityClass, $crudOperation);

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass' => $entityClass,
            'entity' => $entity,
        ];
        $event = new ViewModelEvent($entityClass, $crudOperation, $context, $request);
        $this->getEventDispatcher()->dispatch($event);

        return $this->render($template, $event->context);
    }

    #[Override]
    public function createAction(Request $request): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::CREATE;

        $event = new PreSetDataEvent($entityClass, $crudOperation, $request);
        try {
            $this->getEventDispatcher()->dispatch($event);
        } catch (AbortWithResponseException $e) {
            return $e->response;
        }

        return $this->createOrUpdateAction($request, $crudOperation);
    }

    #[Override]
    public function updateAction(Request $request, mixed $id): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::UPDATE;

        $event = new PreSetDataEvent($entityClass, $crudOperation, $request);
        try {
            $this->getEventDispatcher()->dispatch($event);
        } catch (AbortWithResponseException $e) {
            return $e->response;
        }

        $entity = $this->getItemResolver()->resolveItem($entityClass, $crudOperation, $id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        return $this->createOrUpdateAction($request, $crudOperation, $entity);
    }

    protected function createOrUpdateAction(
        Request $request,
        CrudOperation $crudOperation,
        ?object $entity = null
    ): Response {
        $entityClass = $this->getEntityClass();

        $event = new PostSetDataEvent($entityClass, $crudOperation, $request, $entity);
        try {
            $this->getEventDispatcher()->dispatch($event);
        } catch (AbortWithResponseException $e) {
            return $e->response;
        }

        $form = Asserted::notNull(
            $this->getFormResolver()->resolveForm($crudOperation, $entityClass, $entity),
            sprintf('Could not resolve form for %s:%s', $entityClass, $crudOperation->value)
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = Asserted::instanceOf($form->getData(), $entityClass);
            $this->getEventDispatcher()->dispatch(new PrePersistEvent($entityClass, $crudOperation, $request, $entity));
            $this->getItemPersister()->persistItem($crudOperation, $entityClass, $entity);
            $this->getEventDispatcher()->dispatch(new PostPersistEvent($entityClass, $crudOperation, $request, $entity));

            $event = new RedirectAfterWriteEvent($entityClass, $crudOperation, $entity, $request);
            $this->getEventDispatcher()->dispatch($event);
            if (null !== ($response = $event->response)) {
                return $response;
            }
        }

        $template = $this->fetchTemplate($entityClass, $crudOperation);

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass' => $entityClass,
            'entity' => $entity,
            'form' => $form->createView(),
        ];
        $event = new ViewModelEvent($entityClass, $crudOperation, $context, $request);
        $this->getEventDispatcher()->dispatch($event);

        return $this->render($template, $event->context);
    }

    #[Override]
    public function deleteAction(Request $request, mixed $id): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::DELETE;

        $event = new PreSetDataEvent($entityClass, $crudOperation, $request);
        try {
            $this->getEventDispatcher()->dispatch($event);
        } catch (AbortWithResponseException $e) {
            return $e->response;
        }

        $entity = $this->getItemResolver()->resolveItem($entityClass, $crudOperation, $id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        $event = new PostSetDataEvent($entityClass, $crudOperation, $request, $entity);
        try {
            $this->getEventDispatcher()->dispatch($event);
        } catch (AbortWithResponseException $e) {
            return $e->response;
        }

        $form = $this->getFormFactory()->createBuilder()
            ->add('confirm', SubmitType::class, ['label' => 'delete.confirm', 'translation_domain' => 'DdrCrudAdmin'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEventDispatcher()->dispatch(new PrePersistEvent($entityClass, $crudOperation, $request, $entity));
            $this->getItemPersister()->persistItem($crudOperation, $entityClass, $entity);
            $this->getEventDispatcher()->dispatch(new PostPersistEvent($entityClass, $crudOperation, $request, $entity));

            $event = new RedirectAfterWriteEvent($entityClass, $crudOperation, $entity, $request);
            $this->getEventDispatcher()->dispatch($event);
            if (null !== ($response = $event->response)) {
                return $response;
            }

            $redirectUrl = $this->getUrlResolver()->resolveUrl($entityClass, CrudOperation::LIST, $entity);
            if (
                null !== $redirectUrl
                && $this->getAuthorizationChecker()->isGranted(CrudOperation::LIST->value, $entityClass)
            ) {
                return new RedirectResponse($redirectUrl);
            } else {
                return new Response(status: Response::HTTP_NO_CONTENT);
            }
        }

        $template = $this->fetchTemplate($entityClass, $crudOperation);

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass' => $entityClass,
            'entity' => $entity,
            'form' => $form->createView(),
        ];
        $event = new ViewModelEvent($entityClass, $crudOperation, $context, $request);
        $this->getEventDispatcher()->dispatch($event);

        return $this->render($template, $event->context);
    }

    #[Required]
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    #[Override]
    public static function getSubscribedServices(): array
    {
        return [
            AuthorizationCheckerInterface::class,
            Environment::class,
            UrlGeneratorInterface::class,
            TemplateResolverInterface::class,
            ItemResolverInterface::class,
            FormResolverInterface::class,
            ItemPersisterInterface::class,
            UrlResolverInterface::class,
            EventDispatcherInterface::class,
            PaginationResolverInterface::class,
            FormFactoryInterface::class
        ];
    }

    /**
     * @param class-string $entityClass
     */
    public function fetchTemplate(string $entityClass, CrudOperation $crudOperation): string
    {
        return Asserted::notNull(
            $this->getTemplateResolver()->resolveTemplate($entityClass, $crudOperation),
            sprintf('Could not resolve template for %s:%s', $entityClass, $crudOperation->value)
        );
    }

    /**
     * @param array<string, mixed> $context
     */
    protected function renderView(string $view, array $context = []): string
    {
        return $this->getTwig()->render($view, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    protected function render(string $view, array $context = [], ?Response $response = null): Response
    {
        $content = $this->renderView($view, $context);

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * @param class-string $entityClass
     * @param CrudOperation[] $validCrudOperations
     */
    protected function matches(
        string $entityClass,
        ?CrudOperation $crudOperation = null,
        array $validCrudOperations = []
    ): bool {
        if ($entityClass !== $this->getEntityClass()) {
            return false;
        }

        if (null === $crudOperation) {
            return true;
        }

        if (count($validCrudOperations) === 0) {
            return true;
        }

        return in_array($crudOperation, $validCrudOperations, true);
    }

    protected function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return $this->getContainer()->get(AuthorizationCheckerInterface::class);
    }

    protected function getTwig(): Environment
    {
        return $this->getContainer()->get(Environment::class);
    }

    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->getContainer()->get(FormFactoryInterface::class);
    }

    protected function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->getContainer()->get(UrlGeneratorInterface::class);
    }

    protected function getTemplateResolver(): TemplateResolverInterface
    {
        return $this->getContainer()->get(TemplateResolverInterface::class);
    }

    protected function getItemPersister(): ItemPersisterInterface
    {
        return $this->getContainer()->get(ItemPersisterInterface::class);
    }

    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->getContainer()->get(EventDispatcherInterface::class);
    }

    protected function getItemResolver(): ItemResolverInterface
    {
        return $this->getContainer()->get(ItemResolverInterface::class);
    }

    protected function getUrlResolver(): UrlResolverInterface
    {
        return $this->getContainer()->get(UrlResolverInterface::class);
    }

    protected function getPaginationResolver(): PaginationResolverInterface
    {
        return $this->getContainer()->get(PaginationResolverInterface::class);
    }

    protected function getFormResolver(): FormResolverInterface
    {
        return $this->getContainer()->get(FormResolverInterface::class);
    }

    protected function getContainer(): ContainerInterface
    {
        return Asserted::notNull($this->container, 'Container must not be null');
    }
}
