<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DefaultUrlProvider implements UrlProviderInterface
{
    public function __construct(
        private readonly RouteInfoResolver $routeInfoResolver,
        private readonly RouterInterface $router,
        private readonly IdResolver $idResolver,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsUrl(string $crudOperation, string $entityClass, ?object $entity): bool
    {
        return null !== $this->routeInfoResolver->resolve($crudOperation, $entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function provideUrl(string $crudOperation, string $entityClass, ?object $entity): string
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
