<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteProvider;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\Utils\ClassNameUtils;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultRouteProvider implements RouteProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminRequest $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideRoutes(CrudAdminRequest $request): ?array
    {
        $tableizedName = ClassNameUtils::getTableizedShortName($request->getEntityClass());

        return [
            CrudOperation::LIST   => 'ddr_crud_admin.' . $tableizedName . '.list',
            CrudOperation::CREATE => 'ddr_crud_admin.' . $tableizedName . '.create',
            CrudOperation::READ   => 'ddr_crud_admin.' . $tableizedName . '.read',
            CrudOperation::UPDATE => 'ddr_crud_admin.' . $tableizedName . '.update',
            CrudOperation::DELETE => 'ddr_crud_admin.' . $tableizedName . '.delete',
        ];
    }
}
