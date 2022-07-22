<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\ClassNameUtils;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Symfony\Component\String\Inflector\EnglishInflector;

class DefaultRouteInfoProvider implements RouteInfoProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportRouteInfo(string $crudOperation, string $entityClass): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideRouteInfo(string $crudOperation, string $entityClass): RouteInfo
    {
        $namePrefix = sprintf("ddr_crud_admin.%s.", ClassNameUtils::getTableizedShortName($entityClass));
        $shortName = ClassNameUtils::getShortName($entityClass);
        $pathPrefix = '/' . mb_strtolower((new EnglishInflector())->pluralize($shortName)[0]);

        return self::getRouteInfo($crudOperation, $namePrefix, $pathPrefix);
    }

    public static function getRouteInfo(string $crudOperation, string $namePrefix, string $pathPrefix): RouteInfo
    {
        return match ($crudOperation) {
            CrudOperation::LIST => new RouteInfo($namePrefix . 'list', $pathPrefix),
            CrudOperation::CREATE => new RouteInfo($namePrefix . 'create', $pathPrefix . '/__NEW__/edit'),
            CrudOperation::READ => new RouteInfo($namePrefix . 'read', $pathPrefix . '/{id}'),
            CrudOperation::UPDATE => new RouteInfo($namePrefix . 'update', $pathPrefix . '/{id}/edit'),
            CrudOperation::DELETE => new RouteInfo($namePrefix . 'delete', $pathPrefix . '/{id}/delete',),
        };
    }
}
