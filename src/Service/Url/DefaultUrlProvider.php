<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
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
    public function supportsUrl(CrudOperation $crudOperation, string $entityClass, ?object $entity): bool
    {
        return null !== $this->routeInfoResolver->resolve($crudOperation, $entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function provideUrl(CrudOperation $crudOperation, string $entityClass, ?object $entity): string
    {
        $routeInfo = Asserted::notNull($this->routeInfoResolver->resolve($crudOperation, $entityClass));
        $id = null !== $entity ? $this->idResolver->resolve($crudOperation, $entityClass, $entity) : null;
        return match ($crudOperation) {
            CrudOperation::LIST,
            CrudOperation::CREATE => $this->router->generate($routeInfo->name),
            CrudOperation::READ,
            CrudOperation::DELETE,
            CrudOperation::UPDATE => $this->router->generate($routeInfo->name, ['id' => $id]),
        };
    }
}
