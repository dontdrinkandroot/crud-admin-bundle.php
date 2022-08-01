<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\PostProcessFormEvent;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $crudOperation = CrudOperation::LIST;
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $this->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $pagination = Asserted::notNull(
            $this->getPaginationResolver()->resolvePagination($this->getEntityClass()),
            sprintf('Could not resolve pagination for %s:%s', $this->getEntityClass(), $crudOperation->value)
        );
        $template = Asserted::notNull(
            $this->getTemplateResolver()->resolveTemplate($this->getEntityClass(), $crudOperation),
            sprintf('Could not resolve template for %s:%s', $this->getEntityClass(), $crudOperation->value)
        );

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass'   => $this->getEntityClass(),
            'entities'      => $pagination,
        ];

        return $this->render($template, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function readAction(Request $request, mixed $id): Response
    {
        $crudOperation = CrudOperation::READ;

        $entity = $this->getItemResolver()->resolveItem($this->getEntityClass(), $crudOperation, $id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity)) {
            throw new AccessDeniedException();
        }

        $template = Asserted::notNull(
            $this->getTemplateResolver()->resolveTemplate($this->getEntityClass(), $crudOperation),
            sprintf('Could not resolve template for %s:%s', $this->getEntityClass(), $crudOperation->value)
        );

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass'   => $this->getEntityClass(),
            'entity'        => $entity,
        ];

        return $this->render($template, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function createAction(Request $request): Response
    {
        $crudOperation = CrudOperation::CREATE;
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $this->getEntityClass())) {
            throw new AccessDeniedException();
        }

        return $this->createOrUpdateAction($request, $crudOperation);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAction(Request $request, mixed $id): Response
    {
        $crudOperation = CrudOperation::UPDATE;
        $entity = $this->getItemResolver()->resolveItem($this->getEntityClass(), $crudOperation, $id);
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
        $form = Asserted::notNull(
            $this->getFormResolver()->resolveForm($crudOperation, $this->getEntityClass(), $entity),
            sprintf('Could not resolve form for %s:%s', $this->getEntityClass(), $crudOperation->value)
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = Asserted::instanceOf($form->getData(), $this->getEntityClass());
            $this->getItemPersister()->persistItem($crudOperation, $this->getEntityClass(), $entity);
            $this->getEventDispatcher()->dispatch(
                new PostProcessFormEvent($crudOperation, $this->getEntityClass(), $form, $entity)
            );

            $redirectResponse = $this->findRedirect($crudOperation, $entity);
            if (null !== $redirectResponse) {
                return $redirectResponse;
            }
        }

        $template = Asserted::notNull(
            $this->getTemplateResolver()->resolveTemplate($this->getEntityClass(), $crudOperation),
            sprintf('Could not resolve template for %s:%s', $this->getEntityClass(), $crudOperation->value)
        );

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass'   => $this->getEntityClass(),
            'entity'        => $entity,
            'form'          => $form->createView(),
        ];

        return $this->render($template, $context);
    }

    public function deleteAction(Request $request, mixed $id): Response
    {
        $crudOperation = CrudOperation::DELETE;
        $entity = $this->getItemResolver()->resolveItem($this->getEntityClass(), $crudOperation, $id);

        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity)) {
            throw new AccessDeniedException();
        }

        $this->getItemPersister()->persistItem($crudOperation, $this->getEntityClass(), $entity);

        $redirectResponse = $this->findRedirect($crudOperation, $entity);
        return $redirectResponse ?? new Response('OK');
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
            PaginationResolver::class
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

    protected function renderView(string $view, array $parameters = []): string
    {
        return $this->getTwig()->render($view, $parameters);
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $content = $this->renderView($view, $parameters);

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

    protected function findRedirect(CrudOperation $crudOperation, ?object $entity = null): ?RedirectResponse
    {
        $redirectUrl = $this->getUrlResolver()->resolveUrl($this->getEntityClass(), CrudOperation::LIST, $entity);
        if (
            null !== $redirectUrl
            && $this->getAuthorizationChecker()->isGranted(
                CrudOperation::LIST->value,
                $this->getEntityClass()
            )
        ) {
            return new RedirectResponse($redirectUrl);
        }

        return null;
    }
}
