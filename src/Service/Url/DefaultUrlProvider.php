<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use Override;
use Symfony\Component\Routing\RouterInterface;

/**
 * @template T of object
 * @implements UrlProviderInterface<T>
 */
class DefaultUrlProvider implements UrlProviderInterface
{
    public function __construct(
        private readonly RouteInfoResolverInterface $routeInfoResolver,
        private readonly RouterInterface $router,
        private readonly IdResolver $idResolver,
    ) {
    }

    #[Override]
    public function provideUrl(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string
    {
        $routeInfo = $this->routeInfoResolver->resolveRouteInfo($entityClass, $crudOperation);
        if (null === $routeInfo) {
            return null;
        }
        return match ($crudOperation) {
            CrudOperation::LIST,
            CrudOperation::CREATE => $this->router->generate($routeInfo->name),
            CrudOperation::READ,
            CrudOperation::DELETE,
            CrudOperation::UPDATE => $this->router->generate(
                $routeInfo->name,
                ['id' => null !== $entity ? $this->idResolver->resolveId($entityClass, $crudOperation, $entity) : null]
            ),
        };
    }
}
