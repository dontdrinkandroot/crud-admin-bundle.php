<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use Symfony\Component\Routing\RouterInterface;

class DefaultUrlProvider implements UrlProviderInterface
{
    public function __construct(
        private readonly RouteInfoResolverInterface $routeInfoResolver,
        private readonly RouterInterface $router,
        private readonly IdResolver $idResolver,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provideUrl(string $entityClass, CrudOperation $crudOperation, ?object $entity): string
    {
        $routeInfo = $this->routeInfoResolver->resolveRouteInfo($entityClass, $crudOperation);
        if (null === $routeInfo) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }
        $id = null !== $entity ? $this->idResolver->resolveId($entityClass, $crudOperation, $entity) : null;
        return match ($crudOperation) {
            CrudOperation::LIST,
            CrudOperation::CREATE => $this->router->generate($routeInfo->name),
            CrudOperation::READ,
            CrudOperation::DELETE,
            CrudOperation::UPDATE => $this->router->generate($routeInfo->name, ['id' => $id]),
        };
    }
}
