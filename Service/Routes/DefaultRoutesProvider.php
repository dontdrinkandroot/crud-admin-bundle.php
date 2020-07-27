<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
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
        $tableizedName = ClassNameUtils::getTableizedShortName(RequestAttributes::getEntityClass($request));

        $routes = [];
        foreach (CrudOperation::all() as $crudOperation) {
            $route = 'ddr_crud_admin.' . $tableizedName . '.' . strtolower($crudOperation);
            if (null !== $this->router->getRouteCollection()->get($route)) {
                $routes[$crudOperation] = $route;
            }
        }

       return $routes;
    }
}
