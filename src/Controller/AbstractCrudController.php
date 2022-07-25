<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\PostProcessFormEvent;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
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
abstract class AbstractCrudController implements CrudControllerInterface, ServiceSubscriberInterface
{
    public const NEW_ID = '__NEW__';

    protected ?ContainerInterface $container = null;

    public function getRouteInfo(CrudOperation $crudOperation): ?RouteInfo
    {
        return $this->getRouteInfoResolver()->resolve($crudOperation, $this->getEntityClass());
    }

    public function listAction(Request $request): Response
    {
        $crudOperation = CrudOperation::LIST;
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $this->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $pagination = $this->getPaginationResolver()->resolve($this->getEntityClass());

        $context = [
            'entityClass'       => $this->getEntityClass(),
            'title'             => $this->getTitle($crudOperation),
            'entities'          => $pagination,
            'fieldDefinitions'  => $this->getFieldDefinitions($crudOperation),
            'translationDomain' => $this->getTranslationDomain($crudOperation)
        ];

        return $this->render($this->getTemplate($crudOperation), $context);
    }

    public function createAction(Request $request): Response
    {
        return $this->updateAction($request, self::NEW_ID);
    }

    public function readAction(Request $request, string $id): Response
    {
        $crudOperation = CrudOperation::READ;
        $entity = $this->findItem($crudOperation, $id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity)) {
            throw new AccessDeniedException();
        }
        $title = $this->getTitle($crudOperation, $entity);
        $translationDomain = $this->getTranslationDomain($crudOperation);
        $fieldDefinitions = $this->getFieldDefinitions($crudOperation);

        $context = [
            'title'             => $title,
            'entity'            => $entity,
            'fieldDefinitions'  => $fieldDefinitions,
            'translationDomain' => $translationDomain
        ];

        return $this->render($this->getTemplate($crudOperation), $context);
    }

    public function updateAction(Request $request, mixed $id): Response
    {
        $crudOperation = self::NEW_ID === $id ? CrudOperation::CREATE : CrudOperation::UPDATE;
        $entity = CrudOperation::UPDATE === $crudOperation
            ? $this->findItem($crudOperation, $id)
            : null;
        if (CrudOperation::UPDATE === $crudOperation && null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity ?? $this->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $title = $this->getTitle($crudOperation, $entity);
        $translationDomain = $this->getTranslationDomain($crudOperation);
        $form = $this->getForm($crudOperation, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = Asserted::instanceOf($form->getData(), $this->getEntityClass());
            $this->getItemPersister()->persistItem($crudOperation, $this->getEntityClass(), $entity);
            $this->getEventDispatcher()->dispatch(
                new PostProcessFormEvent($crudOperation, $this->getEntityClass(), $form, $entity)
            );

            $redirectUrl = $this->getUrl(CrudOperation::LIST);
            if (
                null !== $redirectUrl
                && $this->getAuthorizationChecker()->isGranted(
                    CrudOperation::LIST->value,
                    $this->getEntityClass()
                )
            ) {
                return new RedirectResponse($redirectUrl);
            }
        }

        $context = [
            'entity'            => $entity,
            'title'             => $title,
            'form'              => $form->createView(),
            'translationDomain' => $translationDomain
        ];

        return $this->render($this->getTemplate($crudOperation), $context);
    }

    public function deleteAction(Request $request, mixed $id): Response
    {
        $crudOperation = CrudOperation::DELETE;
        $entity = $this->findItem($crudOperation, $id);

        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation->value, $entity)) {
            throw new AccessDeniedException();
        }

        $this->getItemPersister()->persistItem($crudOperation, $this->getEntityClass(), $entity);
        $redirectUrl = $this->getUrl(CrudOperation::LIST);
        if (null !== $redirectUrl) {
            return new RedirectResponse($redirectUrl);
        }

        return new Response('OK');
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(CrudOperation $crudOperation, ?object $entity = null): ?string
    {
        return $this->getUrlResolver()->resolve($crudOperation, $this->getEntityClass(), $entity);
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
            PaginatorInterface::class,
            UrlGeneratorInterface::class,
            TemplateResolver::class,
            FieldDefinitionsResolver::class,
            IdResolver::class,
            ItemResolver::class,
            FormResolver::class,
            TitleResolver::class,
            TranslationDomainResolver::class,
            TranslatorInterface::class,
            FormFactoryInterface::class,
            PaginationTargetResolver::class,
            ItemPersister::class,
            RouteInfoResolver::class,
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

    protected function getPaginator(): PaginatorInterface
    {
        return $this->getContainer()->get(PaginatorInterface::class);
    }

    protected function getTemplateResolver(): TemplateResolver
    {
        return $this->getContainer()->get(TemplateResolver::class);
    }

    protected function getFieldDefinitionsResolver(): FieldDefinitionsResolver
    {
        return $this->getContainer()->get(FieldDefinitionsResolver::class);
    }

    protected function getItemPersister(): ItemPersister
    {
        return $this->getContainer()->get(ItemPersister::class);
    }

    protected function getRouteInfoResolver(): RouteInfoResolver
    {
        return $this->getContainer()->get(RouteInfoResolver::class);
    }

    protected function getIdResolver(): IdResolver
    {
        return $this->getContainer()->get(IdResolver::class);
    }

    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->getContainer()->get(EventDispatcherInterface::class);
    }

    protected function getTitleResolver(): TitleResolver
    {
        return $this->getContainer()->get(TitleResolver::class);
    }

    protected function getTranslator(): TranslatorInterface
    {
        return $this->getContainer()->get(TranslatorInterface::class);
    }

    protected function getTranslationDomainResolver(): TranslationDomainResolver
    {
        return $this->getContainer()->get(TranslationDomainResolver::class);
    }

    protected function getItemResolver(): ItemResolver
    {
        return $this->getContainer()->get(ItemResolver::class);
    }

    protected function getUrlResolver(): UrlResolver
    {
        return $this->getContainer()->get(UrlResolver::class);
    }

    protected function getFormResolver(): FormResolver
    {
        return $this->getContainer()->get(FormResolver::class);
    }

    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->getContainer()->get(FormFactoryInterface::class);
    }

    protected function getPaginationTargetResolver(): PaginationTargetResolver
    {
        return $this->getContainer()->get(PaginationTargetResolver::class);
    }

    protected function getPaginationResolver(): PaginationResolver
    {
        return $this->getContainer()->get(PaginationResolver::class);
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

    protected function getTemplate(CrudOperation $crudOperation): string
    {
        return Asserted::notNull($this->getTemplateResolver()->resolve($crudOperation, $this->getEntityClass()));
    }

    protected function getTranslationDomain(CrudOperation $crudOperation): ?string
    {
        return $this->getTranslationDomainResolver()->resolve($crudOperation, $this->getEntityClass());
    }

    /**
     * @param CrudOperation $crudOperation
     * @param T|null $entity
     *
     * @return string
     */
    protected function getTitle(CrudOperation $crudOperation, ?object $entity = null): string
    {
        return Asserted::notNull($this->getTitleResolver()->resolve($crudOperation, $this->getEntityClass(), $entity));
    }

    /**
     * @param CrudOperation $crudOperation
     *
     * @return array<array-key,FieldDefinition>
     */
    protected function getFieldDefinitions(CrudOperation $crudOperation): array
    {
        return Asserted::notNull(
            $this->getFieldDefinitionsResolver()->resolve($crudOperation, $this->getEntityClass())
        );
    }

    protected function getPaginationTarget(): mixed
    {
        return $this->getPaginationTargetResolver()->resolve($this->getEntityClass());
    }

    /**
     * @param CrudOperation $crudOperation
     * @param T      $entity
     *
     * @return mixed
     */
    protected function getId(CrudOperation $crudOperation, object $entity): mixed
    {
        return $this->getIdResolver()->resolve($crudOperation, $this->getEntityClass(), $entity);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param mixed  $id
     *
     * @return T|null
     */
    protected function findItem(CrudOperation $crudOperation, mixed $id): ?object
    {
        return $this->getItemResolver()->resolve($crudOperation, $this->getEntityClass(), $id);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param T|null $entity
     *
     * @return FormInterface
     */
    protected function getForm(CrudOperation $crudOperation, ?object $entity): FormInterface
    {
        return Asserted::notNull($this->getFormResolver()->resolve($crudOperation, $this->getEntityClass(), $entity));
    }
}
