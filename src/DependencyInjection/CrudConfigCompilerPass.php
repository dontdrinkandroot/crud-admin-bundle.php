<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Controller\ConfigurableCrudController;
use Dontdrinkandroot\CrudAdminBundle\Controller\ConfigurableCrudControllerFactory;
use Dontdrinkandroot\CrudAdminBundle\Controller\CrudController;
use Dontdrinkandroot\CrudAdminBundle\Service\Configuration\YamlFileLoader;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\StaticFieldDefinitionsProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\StaticFormTypeProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\StaticRouteInfoProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Sort\StaticDefaultSortProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\StaticTemplateProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\StaticTranslationDomainProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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

        foreach ($paths as $path) {
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

                if (null !== $config) {
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

                    if (array_key_exists('field_definitions', $config)) {
                        $fieldDefinitionsByCrudOperation = Asserted::array($config['field_definitions']);
                        $container
                            ->register($idPrefix . 'field_definitions_provider', StaticFieldDefinitionsProvider::class)
                            ->addArgument($entityClass)
                            ->addArgument($fieldDefinitionsByCrudOperation)
                            ->addTag(DdrCrudAdminExtension::TAG_FIELD_DEFINITIONS_PROVIDER, ['priority' => -150]);
                    }

                    if (array_key_exists('templates', $config)) {
                        $templates = Asserted::array($config['templates']);
                        $container
                            ->register($idPrefix . 'template_provider', StaticTemplateProvider::class)
                            ->addArgument($entityClass)
                            ->addArgument($templates)
                            ->addTag(DdrCrudAdminExtension::TAG_TEMPLATE_PROVIDER, ['priority' => -150]);
                    }

                    if (array_key_exists('translation_domain', $config)) {
                        $translationDomain = Asserted::string($config['translation_domain']);
                        $container
                            ->register($idPrefix . 'translation_domain', StaticTranslationDomainProvider::class)
                            ->addArgument($entityClass)
                            ->addArgument($translationDomain)
                            ->addTag(DdrCrudAdminExtension::TAG_TRANSLATION_DOMAIN_PROVIDER, ['priority' => -150]);
                    }
                }
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
