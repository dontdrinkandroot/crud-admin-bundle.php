<?php

namespace Dontdrinkandroot\CrudAdminBundle\Routing;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudControllerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use Override;
use RuntimeException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class CrudRoutesLoader extends Loader
{
    public const string TYPE = 'ddr_crud';

    private bool $loaded = false;

    /**
     * @param CrudControllerRegistry<object> $controllerRegistry
     */
    public function __construct(
        private readonly CrudControllerRegistry $controllerRegistry,
        private readonly RouteInfoResolverInterface $routeInfoResolver
    ) {
        parent::__construct();
    }

    #[Override]
    public function supports($resource, ?string $type = null): bool
    {
        return self::TYPE === $type;
    }

    #[Override]
    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        if (true === $this->loaded) {
            throw new RuntimeException(sprintf('Do not add the "%s" loader twice', self::TYPE));
        }

        $routes = new RouteCollection();
        foreach ($this->controllerRegistry->getControllersByServiceId() as $id => $controller) {

            $entityClass = $controller->getEntityClass();

            if (null !== $routeInfo = $this->routeInfoResolver->resolveRouteInfo($entityClass, CrudOperation::LIST)) {
                $defaults = [
                    '_controller' => $id . '::listAction',
                ];
                $requirements = [
                ];
                $route = new Route(
                    path: $routeInfo->path,
                    defaults: $defaults,
                    requirements: $requirements,
                    methods: ['GET']
                );
                $routes->add($routeInfo->name, $route);
            }

            if (null !== $routeInfo = $this->routeInfoResolver->resolveRouteInfo($entityClass, CrudOperation::CREATE)) {
                $defaults = [
                    '_controller' => $id . '::createAction',
                ];
                $requirements = [
                ];
                $route = new Route(
                    path: $routeInfo->path,
                    defaults: $defaults,
                    requirements: $requirements,
                    methods: ['GET', 'POST']
                );
                $routes->add($routeInfo->name, $route);
            }

            if (null !== $routeInfo = $this->routeInfoResolver->resolveRouteInfo($entityClass, CrudOperation::READ)) {
                $defaults = [
                    '_controller' => $id . '::readAction',
                ];
                $requirements = [
                ];
                $route = new Route(
                    path: $routeInfo->path,
                    defaults: $defaults,
                    requirements: $requirements,
                    methods: ['GET']
                );
                $routes->add($routeInfo->name, $route);
            }

            if (null !== $routeInfo = $this->routeInfoResolver->resolveRouteInfo($entityClass, CrudOperation::UPDATE)) {
                $defaults = [
                    '_controller' => $id . '::updateAction',
                ];
                $requirements = [
                ];
                $route = new Route(
                    path: $routeInfo->path,
                    defaults: $defaults,
                    requirements: $requirements,
                    methods: ['GET', 'POST']
                );
                $routes->add($routeInfo->name, $route);
            }

            if (null !== $routeInfo = $this->routeInfoResolver->resolveRouteInfo($entityClass, CrudOperation::DELETE)) {
                $defaults = [
                    '_controller' => $id . '::deleteAction',
                ];
                $requirements = [
                ];
                $route = new Route(
                    path: $routeInfo->path,
                    defaults: $defaults,
                    requirements: $requirements,
                    methods: ['GET', 'POST']
                );
                $routes->add($routeInfo->name, $route);
            }
        }

        $this->loaded = true;

        return $routes;
    }

}
