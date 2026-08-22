<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Routing;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Controller\CrudControllerInterface;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Routing\CrudRoutesLoader;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudControllerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CrudRoutesLoaderTest extends TestCase
{
    public function testSupports(): void
    {
        $loader = $this->createLoader();

        self::assertTrue($loader->supports('foo', 'ddr_crud'));
        self::assertFalse($loader->supports('foo', 'ddr_crud_admin'));
        self::assertFalse($loader->supports('foo', null));
    }

    public function testLoad(): void
    {
        $controller = self::createStub(CrudControllerInterface::class);
        $controller->method('getEntityClass')->willReturn(Department::class);

        $registry = new CrudControllerRegistry();
        $registry->registerController('ddr_crud.entity.App_Entity_Foo.controller', $controller);

        $routeInfoResolver = self::createStub(RouteInfoResolverInterface::class);
        $routeInfoResolver
            ->method('resolveRouteInfo')
            ->willReturnMap([
                [Department::class, CrudOperation::LIST, new RouteInfo('app.foo.list', '/foos/')],
                [Department::class, CrudOperation::CREATE, new RouteInfo('app.foo.create', '/foos/__NEW__/edit')],
                [Department::class, CrudOperation::READ, new RouteInfo('app.foo.read', '/foos/{id}')],
                [Department::class, CrudOperation::UPDATE, new RouteInfo('app.foo.update', '/foos/{id}/edit')],
                [Department::class, CrudOperation::DELETE, null],
            ]);

        $loader = new CrudRoutesLoader($registry, $routeInfoResolver);
        $routes = $loader->load('resource', 'ddr_crud');

        self::assertCount(4, $routes);

        $listRoute = $routes->get('app.foo.list');
        self::assertNotNull($listRoute);
        self::assertEquals('/foos/', $listRoute->getPath());
        self::assertEquals(['GET'], $listRoute->getMethods());
        self::assertEquals(['_controller' => 'ddr_crud.entity.App_Entity_Foo.controller::listAction'], $listRoute->getDefaults());

        $createRoute = $routes->get('app.foo.create');
        self::assertNotNull($createRoute);
        self::assertEquals(['GET', 'POST'], $createRoute->getMethods());

        $readRoute = $routes->get('app.foo.read');
        self::assertNotNull($readRoute);
        self::assertEquals(['GET'], $readRoute->getMethods());
        self::assertEquals(['_controller' => 'ddr_crud.entity.App_Entity_Foo.controller::readAction'], $readRoute->getDefaults());

        $updateRoute = $routes->get('app.foo.update');
        self::assertNotNull($updateRoute);
        self::assertEquals(['GET', 'POST'], $updateRoute->getMethods());
        self::assertEquals(['_controller' => 'ddr_crud.entity.App_Entity_Foo.controller::updateAction'], $updateRoute->getDefaults());

        self::assertNull($routes->get('app.foo.delete'));
    }

    public function testLoadTwiceThrowsException(): void
    {
        $loader = $this->createLoader();

        $loader->load('resource', 'ddr_crud');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Do not add the "ddr_crud" loader twice');

        $loader->load('resource', 'ddr_crud');
    }

    private function createLoader(): CrudRoutesLoader
    {
        $controller = self::createStub(CrudControllerInterface::class);
        $controller->method('getEntityClass')->willReturn(Department::class);

        $registry = new CrudControllerRegistry();
        $registry->registerController('ddr_crud.entity.App_Entity_Foo.controller', $controller);

        $routeInfoResolver = self::createStub(RouteInfoResolverInterface::class);
        $routeInfoResolver->method('resolveRouteInfo')->willReturn(null);

        return new CrudRoutesLoader($registry, $routeInfoResolver);
    }
}