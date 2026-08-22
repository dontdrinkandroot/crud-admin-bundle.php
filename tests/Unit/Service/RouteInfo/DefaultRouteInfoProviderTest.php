<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\DefaultRouteInfoProvider;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;
use PHPUnit\Framework\TestCase;

class DefaultRouteInfoProviderTest extends TestCase
{
    public function testGetRouteInfo(): void
    {
        self::assertEquals(
            new RouteInfo('ddr_crud.example.list', '/examples/'),
            DefaultRouteInfoProvider::getRouteInfo(CrudOperation::LIST, 'ddr_crud.example', '/examples')
        );
        self::assertEquals(
            new RouteInfo('ddr_crud.example.create', '/examples/__NEW__/edit'),
            DefaultRouteInfoProvider::getRouteInfo(CrudOperation::CREATE, 'ddr_crud.example', '/examples')
        );
        self::assertEquals(
            new RouteInfo('ddr_crud.example.read', '/examples/{id}'),
            DefaultRouteInfoProvider::getRouteInfo(CrudOperation::READ, 'ddr_crud.example', '/examples')
        );
        self::assertEquals(
            new RouteInfo('ddr_crud.example.update', '/examples/{id}/edit'),
            DefaultRouteInfoProvider::getRouteInfo(CrudOperation::UPDATE, 'ddr_crud.example', '/examples')
        );
        self::assertEquals(
            new RouteInfo('ddr_crud.example.delete', '/examples/{id}/delete'),
            DefaultRouteInfoProvider::getRouteInfo(CrudOperation::DELETE, 'ddr_crud.example', '/examples')
        );
    }

    public function testProvideRouteInfo(): void
    {
        $provider = new DefaultRouteInfoProvider();

        $routeInfo = $provider->provideRouteInfo(Department::class, CrudOperation::LIST);

        self::assertEquals('ddr_crud.department.list', $routeInfo->name);
        self::assertEquals('/departments/', $routeInfo->path);
    }
}