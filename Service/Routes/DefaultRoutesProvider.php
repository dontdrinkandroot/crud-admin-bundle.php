<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesProviderInterface;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultRoutesProvider implements RoutesProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideRoutes(Request $request): ?array
    {
        $crudAdminRequest = new CrudAdminRequest($request);
        $tableizedName = ClassNameUtils::getTableizedShortName($crudAdminRequest->getEntityClass());

        return [
            CrudOperation::LIST   => 'ddr_crud_admin.' . $tableizedName . '.list',
            CrudOperation::CREATE => 'ddr_crud_admin.' . $tableizedName . '.create',
            CrudOperation::READ   => 'ddr_crud_admin.' . $tableizedName . '.read',
            CrudOperation::UPDATE => 'ddr_crud_admin.' . $tableizedName . '.update',
            CrudOperation::DELETE => 'ddr_crud_admin.' . $tableizedName . '.delete',
        ];
    }
}