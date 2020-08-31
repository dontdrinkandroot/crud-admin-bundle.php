<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\Utils\ClassNameUtils;
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
