<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\PostProcessFormEvent;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @template T of object
 *
 * @implements CrudControllerInterface<T>
 */
abstract class AbstractCrudController
    implements CrudControllerInterface, ServiceSubscriberInterface, TitleProviderInterface, RouteInfoProviderInterface,
               FormTypeProviderInterface, IdProviderInterface, ItemProviderInterface, TemplateProviderInterface
{
    public const NEW_ID = '__NEW__';

    protected ?ContainerInterface $container = null;

    public function listAction(Request $request): Response
    {
        $crudOperation = CrudOperation::LIST;
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $this->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $pagination = $this->getPaginationResolver()->resolve($this->getEntityClass());
        $template = Asserted::notNull(
            $this->getTemplateResolver()->resolve($crudOperation, $this->getEntityClass()),
            sprintf('Could not resolve template for %s::%s', $crudOperation->value, $this->getEntityClass())
        );

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass'   => $this->getEntityClass(),
            'entities'      => $pagination,
        ];

        return $this->render($template, $context);
    }

    public function createAction(Request $request): Response
    {
        return $this->updateAction($request, self::NEW_ID);
    }

    public function readAction(Request $request, string $id): Response
    {
        $crudOperation = CrudOperation::READ;

        $entity = $this->getItemResolver()->resolve($crudOperation, $this->getEntityClass(), $id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity)) {
            throw new AccessDeniedException();
        }

        $template = Asserted::notNull(
            $this->getTemplateResolver()->resolve($crudOperation, $this->getEntityClass()),
            sprintf('Could not resolve template for %s::%s', $crudOperation->value, $this->getEntityClass())
        );

        $context = [
            'crudOperation' => $crudOperation->value,
            'entityClass'   => $this->getEntityClass(),
            'entity'        => $entity,
        ];

        return $this->render($template, $context);
    }

    public function updateAction(Request $request, mixed $id): Response
    {
        $crudOperation = self::NEW_ID === $id ? CrudOperation::CREATE : CrudOperation::UPDATE;
        $entity = CrudOperation::UPDATE === $crudOperation
            ? $this->getItemResolver()->resolve($crudOperation, $this->getEntityClass(), $id)
            : null;
        if (CrudOperation::UPDATE === $crudOperation && null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity ?? $this->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $form = Asserted::notNull(
            $this->getFormResolver()->resolve($crudOperation, $this->getEntityClass(), $entity),
            sprintf('Form not found for %s::%s', $crudOperation->value, $this->getEntityClass())
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
            $this->getTemplateResolver()->resolve($crudOperation, $this->getEntityClass()),
            sprintf('Could not resolve template for %s::%s', $crudOperation->value, $this->getEntityClass())
        );

        $context = [
            'crudOperation' => $crudOperation,
            'entityClass'   => $this->getEntityClass(),
            'entity'        => $entity,
            'form'          => $form->createView(),
        ];

        return $this->render($template, $context);
    }

    public function deleteAction(Request $request, mixed $id): Response
    {
        $crudOperation = CrudOperation::DELETE;
        $entity = $this->getItemResolver()->resolve($crudOperation, $this->getEntityClass(), $id);

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
            TemplateResolver::class,
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

    protected function getTemplateResolver(): TemplateResolver
    {
        return $this->getContainer()->get(TemplateResolver::class);
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
        return Asserted::notNull($this->container);
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
        $redirectUrl = $this->getUrlResolver()->resolve(CrudOperation::LIST, $this->getEntityClass(), $entity);
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

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideTitle(CrudOperation $crudOperation, string $entityClass, ?object $entity): string
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass, $entity);
        }

        return $this->getTitle($crudOperation, $entity);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param T|null        $entity
     *
     * @return string
     * @throws UnsupportedByProviderException
     */
    protected function getTitle(CrudOperation $crudOperation, ?object $entity): string
    {
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass(), $entity);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideRouteInfo(CrudOperation $crudOperation, string $entityClass): RouteInfo
    {
        if ($this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
        }

        return $this->getRouteInfo($crudOperation);
    }

    protected function getRouteInfo(CrudOperation $crudOperation): RouteInfo
    {
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass());
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideFormType(CrudOperation $crudOperation, string $entityClass, ?object $entity): string
    {
        if ($this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass, $entity);
        }

        return $this->getFormType($crudOperation, $entity);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param T|null        $entity
     *
     * @return class-string<FormTypeInterface>
     * @throws UnsupportedByProviderException
     */
    protected function getFormType(CrudOperation $crudOperation, ?object $entity): string
    {
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass(), $entity);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideId(CrudOperation $crudOperation, string $entityClass, object $entity): mixed
    {
        if ($this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass, $entity);
        }

        return $this->getId($crudOperation, $entity);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param T             $entity
     *
     * @return mixed
     * @throws UnsupportedByProviderException
     */
    protected function getId(CrudOperation $crudOperation, object $entity): mixed
    {
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass(), $entity);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideItem(CrudOperation $crudOperation, string $entityClass, mixed $id): ?object
    {
        if ($this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
        }

        return $this->getItem($crudOperation, $id);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param mixed         $id
     *
     * @return T|null
     * @throws UnsupportedByProviderException
     */
    public function getItem(CrudOperation $crudOperation, mixed $id): ?object
    {
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass());
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideTemplate(CrudOperation $crudOperation, string $entityClass): string
    {
        if ($this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
        }

        return $this->getTemplate($crudOperation);
    }

    /**
     * @param CrudOperation $crudOperation
     *
     * @return string
     * @throws UnsupportedByProviderException
     */
    public function getTemplate(CrudOperation $crudOperation): string
    {
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass());
    }
}
