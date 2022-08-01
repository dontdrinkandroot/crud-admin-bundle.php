<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Controller\ConfigurableCrudController;
use Dontdrinkandroot\CrudAdminBundle\Controller\ConfigurableCrudControllerFactory;
use Dontdrinkandroot\CrudAdminBundle\Controller\CrudController;
use Dontdrinkandroot\CrudAdminBundle\Service\Configuration\YamlFileLoader;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\StaticFormTypeProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\StaticRouteInfoProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Sort\StaticDefaultSortProvider;
use Symfony\Component\Config\FileLocator;
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
        $path = $projectDir . '/config/ddr_crud_admin';
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

            $configLoader = new YamlFileLoader(new FileLocator($path));

            foreach (Finder::create()->files()->in($path)->name('/\.(ya?ml)$/') as $file) {
                $config = $configLoader->load($file);
                $entityClass = Asserted::string(array_key_first($config), 'Could not find EntityClass');
                $config = $config[$entityClass];
                $idPrefix = sprintf('ddr_crud.entity.%s.', str_replace('\\', '_', $entityClass));

                $container
                    ->register($idPrefix . 'controller', CrudController::class)
                    ->setAutoconfigured(true)
                    ->setAutowired(true)
                    ->addArgument($entityClass)
                    ->addTag(DdrCrudAdminExtension::TAG_CONTROLLER)
                    ->addTag('controller.service_arguments')
                    ->addTag('container.service_subscriber')
                    ->setPublic(true);

                if (array_key_exists('form_type', $config)) {
                    $formType = Asserted::string($config['form_type']);
                    $container
                        ->register($idPrefix . 'form_type_provider', StaticFormTypeProvider::class)
                        ->addArgument($entityClass)
                        ->addArgument($formType)
                        ->addTag(DdrCrudAdminExtension::TAG_FORM_TYPE_PROVIDER, ['priority' => -150]);
                }

                if (array_key_exists('route', $config)) {
                    $route = Asserted::array($config['route']);
                    $container
                        ->register($idPrefix . 'route_info_provider', StaticRouteInfoProvider::class)
                        ->addArgument($entityClass)
                        ->addArgument($route['name_prefix'] ?? null)
                        ->addArgument($route['path_prefix'] ?? null)
                        ->addTag(DdrCrudAdminExtension::TAG_ROUTE_INFO_PROVIDER, ['priority' => -150]);
                }

                if (array_key_exists('default_sort', $config)) {
                    $defaultSort = Asserted::array($config['default_sort']);
                    $container
                        ->register($idPrefix . 'default_sort_provider', StaticDefaultSortProvider::class)
                        ->addArgument($entityClass)
                        ->addArgument($defaultSort['field'])
                        ->addArgument($defaultSort['order'])
                        ->addTag(DdrCrudAdminExtension::TAG_DEFAULT_SORT_PROVIDER, ['priority' => -150]);
                }

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
                    ->addTag(DdrCrudAdminExtension::TAG_TEMPLATE_PROVIDER)
                    ->addTag(DdrCrudAdminExtension::TAG_FIELD_DEFINITIONS_PROVIDER)
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
