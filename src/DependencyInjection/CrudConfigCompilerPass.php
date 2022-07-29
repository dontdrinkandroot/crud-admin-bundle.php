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
        $paths = $this->getBundlesResourcesPaths($container);
        $projectDir = Asserted::string($container->getParameter('kernel.project_dir'));
        $path = $projectDir . '/config/tks_doctrine_admin';
        if ($container->fileExists($path, '/\.(ya?ml)$/')) {
            $paths['app'] = $path;
        }

        $factory = $container
            ->register(ConfigurableCrudControllerFactory::class, ConfigurableCrudControllerFactory::class)
            ->addArgument(new Reference('serializer'));

        foreach ($paths as $bundleName => $path) {
            if (!is_dir($path)) {
                continue;
            }

            foreach (Finder::create()->files()->in($path)->name('/\.(ya?ml)$/') as $file) {
                $realFilePath = $file->getRealPath();
                $alias = sprintf(
                    "ddr_crud.configurable_controller.%s.%s",
                    $bundleName,
                    $file->getFilenameWithoutExtension()
                );
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

    private function getBundlesResourcesPaths(ContainerBuilder $container): array
    {
        $bundlesResourcesPaths = [];

        $bundleMetadata = $container->getParameter('kernel.bundles_metadata');
        assert(is_array($bundleMetadata));
        foreach ($bundleMetadata as $bundleName => $bundle) {
            $paths = [];
            $dirname = $bundle['path'];
            $paths[] = "$dirname/Resources/config/ddr_crud_admin";
            $paths[] = "$dirname/config/ddr_crud_admin";

            foreach ($paths as $path) {
                if ($container->fileExists($path, false)) {
                    $bundlesResourcesPaths[$bundleName] = $path;
                }
            }
        }

        return $bundlesResourcesPaths;
    }
}
