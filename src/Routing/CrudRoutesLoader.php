<?php

namespace Dontdrinkandroot\CrudAdminBundle\Routing;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudControllerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use RuntimeException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class CrudRoutesLoader extends Loader
{
    private bool $loaded = false;

    public function __construct(
        private readonly CrudControllerRegistry $controllerRegistry,
        private readonly RouteInfoResolverInterface $routeInfoResolver
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, string $type = null): bool
    {
        return 'ddr_crud' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, string $type = null): RouteCollection
    {
        if (true === $this->loaded) {
            throw new RuntimeException('Do not add the "ddr_crud" loader twice');
        }

        $routes = new RouteCollection();
        foreach ($this->controllerRegistry->getControllersByServiceId() as $id => $controller) {

            /** @var class-string $entityClass */
            $entityClass = $controller->getEntityClass();

            if (null !== $routeInfo = $this->routeInfoResolver->resolve(CrudOperation::LIST, $entityClass)) {
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

            if (null !== $routeInfo = $this->routeInfoResolver->resolve(CrudOperation::CREATE, $entityClass)) {
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

            if (null !== $routeInfo = $this->routeInfoResolver->resolve(CrudOperation::READ, $entityClass)) {
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

            if (null !== $routeInfo = $this->routeInfoResolver->resolve(CrudOperation::UPDATE, $entityClass)) {
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

            if (null !== $routeInfo = $this->routeInfoResolver->resolve(CrudOperation::DELETE, $entityClass)) {
                $defaults = [
                    '_controller' => $id . '::deleteAction',
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
        }

        $this->loaded = true;

        return $routes;
    }

}
