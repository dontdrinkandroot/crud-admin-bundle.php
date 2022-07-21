<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\ClassNameUtils;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;
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
use Twig\Environment;

use function PHPUnit\Framework\matches;

/**
 * @template T of object
 *
 * @implements CrudControllerInterface<T>
 */
abstract class AbstractCrudController implements CrudControllerInterface, ServiceSubscriberInterface
{
    protected ?ContainerInterface $container = null;

    /**
     * {@inheritdoc}
     */
    public function isOperationEnabled(string $crudOperation): bool
    {
        return true;
    }

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
            CrudOperation::READ => new RouteInfo($namePrefix . 'get', $pathPrefix . '/{id}'),
            CrudOperation::UPDATE => new RouteInfo($namePrefix . 'update', $pathPrefix . '/{id}/edit'),
            CrudOperation::DELETE => new RouteInfo($namePrefix . 'delete', $pathPrefix . '/{id}/delete',),
        };
    }

    public function listAction(Request $request): Response
    {
        if (!$this->getAuthorizationChecker()->isGranted(CrudOperation::LIST, $this->getEntityClass())) {
            throw new AccessDeniedException();
        }

        $limit = null;
        if ($request->query->has('perPage')) {
            $limit = $request->query->getInt('perPage');
        }

        $pagination = $this->getPaginator()->paginate(
            $this->getPaginationTarget(),
            $request->query->getInt('page', 1),
            $limit
        );

        $context = [
            'entityClass'       => $this->getEntityClass(),
            'title'             => $this->getTitle(CrudOperation::LIST),
            'entities'          => $pagination,
            'fieldDefinitions'  => $this->getFieldDefinitions(CrudOperation::LIST),
            'translationDomain' => $this->getTranslationDomain()
        ];

        return $this->render($this->getTemplate(CrudOperation::LIST), $context);
    }

    public function createAction(Request $request): Response
    {
        if (!$this->getAuthorizationChecker()->isGranted(CrudOperation::CREATE, $this->getEntityClass())) {
            throw new AccessDeniedException();
        }
        throw new RuntimeException('Not implemented');
    }

    public function readAction(Request $request, string $id): Response
    {
        $entity = $this->findItem($id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$this->getAuthorizationChecker()->isGranted(CrudOperation::READ, $entity)) {
            throw new AccessDeniedException();
        }
        $title = $this->getTitle(CrudOperation::READ, $entity);
        $fieldDefinitions = $this->getFieldDefinitions(CrudOperation::READ);

        $context = [
            'title'             => $title,
            'entity'            => $entity,
            'fieldDefinitions'  => $fieldDefinitions,
            'translationDomain' => $this->getTranslationDomain()
        ];

        return $this->render($this->getTemplate(CrudOperation::READ), $context);
    }

    public function updateAction(Request $request, string $id): Response
    {
        throw new RuntimeException('Not implemented');
    }

    public function deleteAction(Request $request, string $id): Response
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(string $crudOperation, ?object $entity): ?string
    {
        $route = $this->getRouteInfo($crudOperation)?->name;
        if (null === $route) {
            return null;
        }

        $id = null !== $entity ? $this->getId($entity) : null;
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
            FieldDefinitionsResolver::class
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

    protected function getTranslationDomain(): ?string
    {
        return null;
    }

    /**
     * @param string      $crudOperation
     * @param T|null $entity
     *
     * @return string
     */
    protected function getTitle(string $crudOperation, ?object $entity = null): string
    {
        return $crudOperation;
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

    abstract protected function getPaginationTarget(): mixed;

    /**
     * @param T $entity
     *
     * @return mixed
     */
    abstract protected function getId(object $entity): mixed;

    /**
     * @param string $id
     *
     * @return T|null
     */
    abstract protected function findItem(string $id): ?object;
}
