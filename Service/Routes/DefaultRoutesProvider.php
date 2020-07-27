<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
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

        return [
            CrudOperation::LIST   => 'ddr_crud_admin.' . $tableizedName . '.list',
            CrudOperation::CREATE => 'ddr_crud_admin.' . $tableizedName . '.create',
            CrudOperation::READ   => 'ddr_crud_admin.' . $tableizedName . '.read',
            CrudOperation::UPDATE => 'ddr_crud_admin.' . $tableizedName . '.update',
            CrudOperation::DELETE => 'ddr_crud_admin.' . $tableizedName . '.delete',
        ];
    }
}
