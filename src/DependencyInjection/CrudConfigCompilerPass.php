<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Controller\ConfigurableCrudController;
use Dontdrinkandroot\CrudAdminBundle\Controller\ConfigurableCrudControllerFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;

class CrudConfigCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $projectDir = Asserted::string($container->getParameter('kernel.project_dir'));
        if (!is_dir($dir = sprintf("%s/config/ddr_crud", $projectDir))) {
            return;
        }

        $factory = $container
            ->register(ConfigurableCrudControllerFactory::class, ConfigurableCrudControllerFactory::class)
            ->addArgument(new Reference('serializer'))
        ;

        foreach (Finder::create()->files()->in($dir)->name('/\.(ya?ml)$/') as $file) {
            $realFilePath = $file->getRealPath();
            $alias = 'ddr_crud.configurable_controller.' . $file->getFilenameWithoutExtension();
            $container->register($alias, ConfigurableCrudController::class)
                ->setFactory([$factory, 'create'])
                ->addArgument($realFilePath)
                ->setAutowired(true)
                ->setAutoconfigured(true)
                ->addTag(DdrCrudAdminExtension::TAG_CONTROLLER)
                ->addTag(DdrCrudAdminExtension::TAG_FORM_TYPE_PROVIDER)
                ->addTag(DdrCrudAdminExtension::TAG_ROUTE_INFO_PROVIDER)
                ->addTag(DdrCrudAdminExtension::TAG_TEMPLATE_PROVIDER)
                ->addTag(DdrCrudAdminExtension::TAG_FIELD_DEFINITIONS_PROVIDER)
                ->addTag(DdrCrudAdminExtension::TAG_DEFAULT_SORT_PROVIDER)
                ->addTag('controller.service_arguments')
                ->addTag('container.service_subscriber')
                ->setPublic(true);
        }
    }
}
