<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\ListModelEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\PostProcessFormEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\ReadModelEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\RedirectAfterWriteEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\ViewModelEvent;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
    public const NEW_ID = '__NEW__';

    protected ?ContainerInterface $container = null;

    /**
     * {@inheritdoc}
     */
    public function listAction(Request $request): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::LIST;

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entityClass)) {
            throw new AccessDeniedException();
        }

        $pagination = Asserted::notNull(
            $this->getPaginationResolver()->resolvePagination($entityClass),
            sprintf('Could not resolve pagination for %s:%s', $entityClass, $crudOperation->value)
        );

        $template = Asserted::notNull(
            $this->getTemplateResolver()->resolveTemplate($entityClass, $crudOperation),
            sprintf('Could not resolve template for %s:%s', $entityClass, $crudOperation->value)
        );

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass'   => $entityClass,
            'entities'      => $pagination,
        ];
        $event = new ViewModelEvent($entityClass, $crudOperation, $context);
        $this->getEventDispatcher()->dispatch($event);

        return $this->render($template, $event->context);
    }

    /**
     * {@inheritdoc}
     */
    public function readAction(Request $request, mixed $id): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::READ;

        $entity = $this->getItemResolver()->resolveItem($entityClass, $crudOperation, $id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity)) {
            throw new AccessDeniedException();
        }

        $template = Asserted::notNull(
            $this->getTemplateResolver()->resolveTemplate($entityClass, $crudOperation),
            sprintf('Could not resolve template for %s:%s', $entityClass, $crudOperation->value)
        );

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass'   => $entityClass,
            'entity'        => $entity,
        ];
        $event = new ViewModelEvent($entityClass, $crudOperation, $context);
        $this->getEventDispatcher()->dispatch($event);

        return $this->render($template, $event->context);
    }

    /**
     * {@inheritdoc}
     */
    public function createAction(Request $request): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::CREATE;

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entityClass)) {
            throw new AccessDeniedException();
        }

        return $this->createOrUpdateAction($request, $crudOperation);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAction(Request $request, mixed $id): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::UPDATE;

        $entity = $this->getItemResolver()->resolveItem($entityClass, $crudOperation, $id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity)) {
            throw new AccessDeniedException();
        }

        return $this->createOrUpdateAction($request, $crudOperation, $entity);
    }

    /**
     * @param Request       $request
     * @param CrudOperation $crudOperation
     * @param object|null   $entity
     *
     * @return Response
     */
    protected function createOrUpdateAction(
        Request $request,
        CrudOperation $crudOperation,
        ?object $entity = null
    ): Response {
        $entityClass = $this->getEntityClass();
        $form = Asserted::notNull(
            $this->getFormResolver()->resolveForm($crudOperation, $entityClass, $entity),
            sprintf('Could not resolve form for %s:%s', $entityClass, $crudOperation->value)
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = Asserted::instanceOf($form->getData(), $entityClass);
            $this->getItemPersister()->persistItem($crudOperation, $entityClass, $entity);

            $event = new RedirectAfterWriteEvent($entityClass, $crudOperation, $entity, $request);
            $this->getEventDispatcher()->dispatch($event);
            if (null !== ($response = $event->response)) {
                return $response;
            }
        }

        $template = Asserted::notNull(
            $this->getTemplateResolver()->resolveTemplate($entityClass, $crudOperation),
            sprintf('Could not resolve template for %s:%s', $entityClass, $crudOperation->value)
        );

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass'   => $entityClass,
            'entity'        => $entity,
            'form'          => $form->createView(),
        ];
        $event = new ViewModelEvent($entityClass, $crudOperation, $context);
        $this->getEventDispatcher()->dispatch($event);

        return $this->render($template, $event->context);
    }

    public function deleteAction(Request $request, mixed $id): Response
    {
        $entityClass = $this->getEntityClass();
        $crudOperation = CrudOperation::DELETE;

        $entity = $this->getItemResolver()->resolveItem($entityClass, $crudOperation, $id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity)) {
            throw new AccessDeniedException();
        }

        $form = $this->getFormFactory()->createBuilder()
            ->add('confirm', SubmitType::class, ['label' => 'delete.confirm', 'translation_domain' => 'DdrCrudAdmin'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getItemPersister()->persistItem($crudOperation, $entityClass, $entity);

            $event = new RedirectAfterWriteEvent($entityClass, $crudOperation, $entity, $request);
            $this->getEventDispatcher()->dispatch($event);
            if (null !== ($response = $event->response)) {
                return $response;
            }
        }

        $template = Asserted::notNull(
            $this->getTemplateResolver()->resolveTemplate($entityClass, $crudOperation),
            sprintf('Could not resolve template for %s:%s', $entityClass, $crudOperation->value)
        );

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass'   => $entityClass,
            'entity'        => $entity,
            'form'          => $form->createView(),
        ];
        $event = new ViewModelEvent($entityClass, $crudOperation, $context);
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

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices(): array
    {
        return [
            AuthorizationCheckerInterface::class,
            Environment::class,
            UrlGeneratorInterface::class,
            TemplateResolverInterface::class,
            ItemResolver::class,
            FormResolver::class,
            ItemPersister::class,
            UrlResolver::class,
            EventDispatcherInterface::class,
            PaginationResolver::class,
            FormFactoryInterface::class
        ];
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

    protected function getItemPersister(): ItemPersister
    {
        return $this->getContainer()->get(ItemPersister::class);
    }

    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->getContainer()->get(EventDispatcherInterface::class);
    }

    protected function getItemResolver(): ItemResolver
    {
        return $this->getContainer()->get(ItemResolver::class);
    }

    protected function getUrlResolver(): UrlResolver
    {
        return $this->getContainer()->get(UrlResolver::class);
    }

    protected function getPaginationResolver(): PaginationResolver
    {
        return $this->getContainer()->get(PaginationResolver::class);
    }

    protected function getFormResolver(): FormResolver
    {
        return $this->getContainer()->get(FormResolver::class);
    }

    protected function getContainer(): ContainerInterface
    {
        return Asserted::notNull($this->container, 'Container must not be null');
    }

    protected function renderView(string $view, array $context = []): string
    {
        return $this->getTwig()->render($view, $context);
    }

    protected function render(string $view, array $context = [], Response $response = null): Response
    {
        $content = $this->renderView($view, $context);

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

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

}
