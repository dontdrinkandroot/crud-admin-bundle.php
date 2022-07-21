<?php

namespace Dontdrinkandroot\CrudAdminBundle\Routing;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Controller\AbstractCrudController;
use Dontdrinkandroot\CrudAdminBundle\Controller\CrudControllerInterface;
use RuntimeException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class CrudRoutesLoader extends Loader
{
    private bool $loaded = false;

    /**
     * @param iterable<CrudControllerInterface> $controllers
     */
    public function __construct(private readonly iterable $controllers)
    {
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
        foreach ($this->controllers as $controller) {

            if (null !== $routeInfo = $controller->getRouteInfo(CrudOperation::LIST)) {
                $defaults = [
                    '_controller' => get_class($controller) . '::listAction',
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

            if (null !== $routeInfo = $controller->getRouteInfo(CrudOperation::CREATE)) {
                $defaults = [
                    '_controller' => get_class($controller) . '::createAction',
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

            if (null !== $routeInfo = $controller->getRouteInfo(CrudOperation::READ)) {
                $defaults = [
                    '_controller' => get_class($controller) . '::readAction',
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

            if (null !== $routeInfo = $controller->getRouteInfo(CrudOperation::UPDATE)) {
                $defaults = [
                    '_controller' => get_class($controller) . '::updateAction',
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

            if (null !== $routeInfo = $controller->getRouteInfo(CrudOperation::DELETE)) {
                $defaults = [
                    '_controller' => get_class($controller) . '::deleteAction',
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
