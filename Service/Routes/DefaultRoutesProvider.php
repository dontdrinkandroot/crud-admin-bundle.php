<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultRoutesProvider implements RoutesProviderInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRequest(Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideRoutes(Request $request): ?array
    {
        $prefix = RequestAttributes::getRoutesPrefix($request);
        if (null === $prefix) {
            $tableizedName = ClassNameUtils::getTableizedShortName(RequestAttributes::getEntityClass($request));
            $prefix = 'ddr_crud_admin.' . $tableizedName . '.';
        }

        $routes = [];
        foreach (CrudOperation::all() as $crudOperation) {
            $route = $prefix . strtolower($crudOperation);
            if (null !== $this->router->getRouteCollection()->get($route)) {
                $routes[$crudOperation] = $route;
            }
        }

       return $routes;
    }
}
