<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\ClassNameUtils;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

use function PHPUnit\Framework\matches;

/**
 * @template T of object
 *
 * @implements CrudControllerInterface<T>
 */
abstract class AbstractCrudController implements CrudControllerInterface, ServiceSubscriberInterface
{
    const NEW_ID = '__NEW__';

    protected ?ContainerInterface $container = null;

    /**
     * {@inheritdoc}
     */
    public function getNamePrefix(): string
    {
        return sprintf("ddr_crud_admin.%s.", ClassNameUtils::getTableizedShortName($this->getEntityClass()));
    }

    /**
     * {@inheritdoc}
     */
    public function getPathPrefix(): string
    {
        $shortName = ClassNameUtils::getShortName($this->getEntityClass());
        return '/' . mb_strtolower((new EnglishInflector())->pluralize($shortName)[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteInfo(string $crudOperation): ?RouteInfo
    {
        $namePrefix = $this->getNamePrefix();
        $pathPrefix = $this->getPathPrefix();
        return match ($crudOperation) {
            CrudOperation::LIST => new RouteInfo($namePrefix . 'list', $pathPrefix),
            CrudOperation::CREATE => new RouteInfo($namePrefix . 'create', $pathPrefix . '/__NEW__/edit'),
            CrudOperation::READ => new RouteInfo($namePrefix . 'read', $pathPrefix . '/{id}'),
            CrudOperation::UPDATE => new RouteInfo($namePrefix . 'update', $pathPrefix . '/{id}/edit'),
            CrudOperation::DELETE => new RouteInfo($namePrefix . 'delete', $pathPrefix . '/{id}/delete',),
        };
    }

    public function listAction(Request $request): Response
    {
        $crudOperation = CrudOperation::LIST;
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation, $this->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $limit = null;
        if ($request->query->has('perPage')) {
            $limit = $request->query->getInt('perPage');
        }

        $pagination = $this->getPaginator()->paginate(
            $this->getPaginationTarget($crudOperation),
            $request->query->getInt('page', 1),
            $limit
        );

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
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation, $entity)) {
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
        if (!$this->getAuthorizationChecker()->isGranted($crudOperation, $entity ?? $this->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $title = $this->getTitle($crudOperation, $entity);
        $translationDomain = $this->getTranslationDomain($crudOperation);
        $form = $this->getForm($crudOperation, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = Asserted::instanceOf($form->getData(), $this->getEntityClass());
            $this->getItemPersister()->persistItem($crudOperation, $this->getEntityClass(), $entity);
            $redirectUrl = $this->getUrl(CrudOperation::LIST);
            if (null !== $redirectUrl) {
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

        if (!$this->getAuthorizationChecker()->isGranted($crudOperation, $entity)) {
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
    public function getUrl(string $crudOperation, ?object $entity = null): ?string
    {
        $route = $this->getRouteInfo($crudOperation)?->name;
        if (null === $route) {
            return null;
        }

        $id = null !== $entity ? $this->getId($crudOperation, $entity) : null;
        return match ($crudOperation) {
            CrudOperation::LIST,
            CrudOperation::CREATE => $this->getUrlGenerator()->generate($route),
            CrudOperation::READ,
            CrudOperation::DELETE,
            CrudOperation::UPDATE => $this->getUrlGenerator()->generate($route, ['id' => $id]),
        };
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
            ItemPersister::class
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

    protected function getIdResolver(): IdResolver
    {
        return $this->getContainer()->get(IdResolver::class);
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

    protected function getTemplate(string $crudOperation): string
    {
        return Asserted::notNull($this->getTemplateResolver()->resolve($crudOperation, $this->getEntityClass()));
    }

    protected function getTranslationDomain(string $crudOperation): ?string
    {
        return $this->getTranslationDomainResolver()->resolve($crudOperation, $this->getEntityClass());
    }

    /**
     * @param string $crudOperation
     * @param T|null $entity
     *
     * @return string
     */
    protected function getTitle(string $crudOperation, ?object $entity = null): string
    {
        return $this->getTitleResolver()->resolve($crudOperation, $this->getEntityClass(), $entity);
    }

    /**
     * @param string $crudOperation
     *
     * @return array<array-key,FieldDefinition>
     */
    protected function getFieldDefinitions(string $crudOperation): array
    {
        return $this->getFieldDefinitionsResolver()->resolve($crudOperation, $this->getEntityClass());
    }

    protected function getPaginationTarget(string $crudOperation): mixed
    {
        return $this->getPaginationTargetResolver()->resolve($crudOperation, $this->getEntityClass());
    }

    /**
     * @param string $crudOperation
     * @param T      $entity
     *
     * @return mixed
     */
    protected function getId(string $crudOperation, object $entity): mixed
    {
        return $this->getIdResolver()->resolve($crudOperation, $this->getEntityClass(), $entity);
    }

    /**
     * @param string $crudOperation
     * @param mixed  $id
     *
     * @return T|null
     */
    protected function findItem(string $crudOperation, mixed $id): ?object
    {
        return $this->getItemResolver()->resolve($crudOperation, $this->getEntityClass(), $id);
    }

    /**
     * @param string $crudOperation
     * @param T|null $entity
     *
     * @return FormInterface
     */
    protected function getForm(string $crudOperation, ?object $entity): FormInterface
    {
        return Asserted::notNull($this->getFormResolver()->resolve($crudOperation, $this->getEntityClass(), $entity));
    }
}
