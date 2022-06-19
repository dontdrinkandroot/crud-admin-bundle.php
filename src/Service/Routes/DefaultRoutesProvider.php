<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\Common\ClassNameUtils;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Symfony\Component\Routing\RouterInterface;

class DefaultRoutesProvider implements RoutesProviderInterface
{
    public function __construct(private RouterInterface $router)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRoutes(CrudAdminContext $context): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideRoutes(CrudAdminContext $context): ?array
    {
        if (!RequestAttributes::entityClassMatches($context)) {
            return null;
        }

        $prefix = RequestAttributes::getRoutesPrefix($context->getRequest());
        if (null === $prefix) {
            $tableizedName = ClassNameUtils::getTableizedShortName($context->getEntityClass());
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
