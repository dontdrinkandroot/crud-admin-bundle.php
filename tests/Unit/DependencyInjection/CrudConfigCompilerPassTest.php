<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\DependencyInjection;

use Dontdrinkandroot\CrudAdminBundle\Controller\CrudController;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\CrudConfigCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\StaticFieldDefinitionsProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\StaticFormTypeProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\StaticRouteInfoProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Sort\StaticDefaultSortProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\StaticTemplateProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\StaticTranslationDomainProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CrudConfigCompilerPassTest extends TestCase
{
    public function testProcess(): void
    {
        $container = $this->createContainer(__DIR__ . '/fixtures/project');

        (new CrudConfigCompilerPass())->process($container);

        $fooControllerId = 'ddr_crud.entity.App_Entity_Foo.controller';
        self::assertTrue($container->hasDefinition($fooControllerId));
        $fooControllerDefinition = $container->getDefinition($fooControllerId);
        self::assertEquals(CrudController::class, $fooControllerDefinition->getClass());
        self::assertSame('App\Entity\Foo', $fooControllerDefinition->getArgument(0));
        self::assertArrayHasKey(DdrCrudAdminExtension::TAG_CONTROLLER, $fooControllerDefinition->getTags());

        $formTypeProviderId = 'ddr_crud.entity.App_Entity_Foo.form_type_provider';
        self::assertTrue($container->hasDefinition($formTypeProviderId));
        self::assertEquals(
            StaticFormTypeProvider::class,
            $container->getDefinition($formTypeProviderId)->getClass()
        );

        $routeInfoProviderId = 'ddr_crud.entity.App_Entity_Foo.route_info_provider';
        self::assertTrue($container->hasDefinition($routeInfoProviderId));
        self::assertEquals(
            StaticRouteInfoProvider::class,
            $container->getDefinition($routeInfoProviderId)->getClass()
        );

        $defaultSortProviderId = 'ddr_crud.entity.App_Entity_Foo.default_sort_provider';
        self::assertTrue($container->hasDefinition($defaultSortProviderId));
        self::assertEquals(
            StaticDefaultSortProvider::class,
            $container->getDefinition($defaultSortProviderId)->getClass()
        );

        $fieldDefinitionsProviderId = 'ddr_crud.entity.App_Entity_Foo.field_definitions_provider';
        self::assertTrue($container->hasDefinition($fieldDefinitionsProviderId));
        self::assertEquals(
            StaticFieldDefinitionsProvider::class,
            $container->getDefinition($fieldDefinitionsProviderId)->getClass()
        );

        $templateProviderId = 'ddr_crud.entity.App_Entity_Foo.template_provider';
        self::assertTrue($container->hasDefinition($templateProviderId));
        self::assertEquals(
            StaticTemplateProvider::class,
            $container->getDefinition($templateProviderId)->getClass()
        );

        $translationDomainId = 'ddr_crud.entity.App_Entity_Foo.translation_domain';
        self::assertTrue($container->hasDefinition($translationDomainId));
        self::assertEquals(
            StaticTranslationDomainProvider::class,
            $container->getDefinition($translationDomainId)->getClass()
        );

        $barControllerId = 'ddr_crud.entity.App_Entity_Bar.controller';
        self::assertTrue($container->hasDefinition($barControllerId));
        self::assertFalse($container->hasDefinition('ddr_crud.entity.App_Entity_Bar.form_type_provider'));
        self::assertFalse($container->hasDefinition('ddr_crud.entity.App_Entity_Bar.field_definitions_provider'));
    }

    public function testProcessWithoutConfig(): void
    {
        $container = $this->createContainer(__DIR__ . '/fixtures/empty');

        (new CrudConfigCompilerPass())->process($container);

        $definitionIds = array_keys($container->getDefinitions());
        $crudDefinitionIds = array_values(
            array_filter(
                $definitionIds,
                static fn(string $id): bool => str_starts_with($id, 'ddr_crud.')
            )
        );
        self::assertSame([], $crudDefinitionIds);
    }

    /**
     * @return ContainerBuilder
     */
    private function createContainer(string $projectDir): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', $projectDir);
        $container->setParameter('kernel.bundles_metadata', [
            'TestBundle' => [
                'name' => 'TestBundle',
                'path' => '/nonexistent',
            ],
        ]);

        return $container;
    }
}
