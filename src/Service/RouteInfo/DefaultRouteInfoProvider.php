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
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): RouteInfo
    {
        return self::getRouteInfo(
            $crudOperation,
            self::getDefaultNamePrefix($entityClass),
            self::getDefaultPathPrefix($entityClass)
        );
    }

    public static function getRouteInfo(CrudOperation $crudOperation, string $namePrefix, string $pathPrefix): RouteInfo
    {
        return match ($crudOperation) {
            CrudOperation::LIST => new RouteInfo($namePrefix . '.list', $pathPrefix . '/'),
            CrudOperation::CREATE => new RouteInfo($namePrefix . '.create', $pathPrefix . '/__NEW__/edit'),
            CrudOperation::READ => new RouteInfo($namePrefix . '.read', $pathPrefix . '/{id}'),
            CrudOperation::UPDATE => new RouteInfo($namePrefix . '.update', $pathPrefix . '/{id}/edit'),
            CrudOperation::DELETE => new RouteInfo($namePrefix . '.delete', $pathPrefix . '/{id}/delete',),
        };
    }

    /**
     * @param class-string $entityClass
     */
    public static function getDefaultNamePrefix(string $entityClass): string
    {
        $tableizedShortName = ClassNameUtils::getTableizedShortName($entityClass);
        return sprintf("ddr_crud.%s", $tableizedShortName);
    }

    /**
     * @param class-string $entityClass
     */
    public static function getDefaultPathPrefix(string $entityClass): string
    {
        $tableizedShortName = ClassNameUtils::getTableizedShortName($entityClass);
        return '/' . mb_strtolower((new EnglishInflector())->pluralize($tableizedShortName)[0]);
    }
}
