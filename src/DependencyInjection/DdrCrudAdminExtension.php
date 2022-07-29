<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Dontdrinkandroot\CrudAdminBundle\Controller\CrudControllerInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRendererProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersisterProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Query\QueryExtensionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\QueryBuilder\QueryBuilderExtensionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Sort\DefaultSortProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrCrudAdminExtension extends Extension
{
    public const TAG_CONTROLLER = 'ddr_crud_admin.controller';
    public const TAG_FORM_TYPE_PROVIDER = 'ddr_crud_admin.form_type_provider';
    public const TAG_TEMPLATE_PROVIDER = 'ddr_crud_admin.template_provider';
    public const TAG_ROUTE_INFO_PROVIDER = 'ddr_crud_admin.route_info_provider';
    public const TAG_ID_PROVIDER = 'ddr_crud_admin.id_provider';
    public const TAG_FIELD_DEFINITIONS_PROVIDER = 'ddr_crud_admin.field_definitions_provider';
    public const TAG_QUERY_EXTENSION_PROVIDER = 'ddr_crud_admin.query_extension_provider';
    public const TAG_DEFAULT_SORT_PROVIDER = 'ddr_crud_admin.default_sort_provider';
    public const TAG_FIELD_RENDERER_PROVIDER = 'ddr_crud_admin.field_renderer_provider';
    public const TAG_PAGINATION_PROVIDER = 'ddr_crud_admin.pagination_provider';
    public const TAG_PAGINATION_TARGET_PROVIDER = 'ddr_crud_admin.pagination_target_provider';
    public const TAG_FORM_PROVIDER = 'ddr_crud_admin.form_provider';
    public const TAG_ITEM_PROVIDER = 'ddr_crud_admin.item_provider';
    public const TAG_TITLE_PROVIDER = 'ddr_crud_admin.title_provider';
    public const TAG_ITEM_PERSISTER_PROVIDER = 'ddr_crud_admin.item_persister_provider';
    public const TAG_URL_PROVIDER = 'ddr_crud_admin.url_provider';
    public const TAG_TRANSLATION_DOMAIN_PROVIDER = 'ddr_crud_admin.translation_domain_provider';
    public const TAG_QUERY_BUILDER_EXTENSION_PROVIDER = 'ddr_crud_admin.query_builder_extension_provider';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerAutoConfigurationTags($container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));
        $loader->load('services.yaml');

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));

        $bundles = $container->getParameter('kernel.bundles');
        if (is_array($bundles) && array_key_exists('DoctrineBundle', $bundles)) {
            $loader->load('doctrine.php');
            $loader->load('serializer.php');
        }
    }

    private function registerAutoConfigurationTags(ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(FieldRendererProviderInterface::class)
            ->addTag(self::TAG_FIELD_RENDERER_PROVIDER);
        $container
            ->registerForAutoconfiguration(FormTypeProviderInterface::class)
            ->addTag(self::TAG_FORM_TYPE_PROVIDER);
        $container
            ->registerForAutoconfiguration(QueryExtensionProviderInterface::class)
            ->addTag(self::TAG_QUERY_EXTENSION_PROVIDER);
        $container
            ->registerForAutoconfiguration(DefaultSortProviderInterface::class)
            ->addTag(self::TAG_DEFAULT_SORT_PROVIDER);
        $container
            ->registerForAutoconfiguration(PaginationProviderInterface::class)
            ->addTag(self::TAG_PAGINATION_PROVIDER);
        $container
            ->registerForAutoconfiguration(PaginationTargetProvider::class)
            ->addTag(self::TAG_PAGINATION_TARGET_PROVIDER);
        $container
            ->registerForAutoconfiguration(FieldDefinitionsProviderInterface::class)
            ->addTag(self::TAG_FIELD_DEFINITIONS_PROVIDER);
        $container
            ->registerForAutoconfiguration(FormProviderInterface::class)
            ->addTag(self::TAG_FORM_PROVIDER);
        $container
            ->registerForAutoconfiguration(ItemProviderInterface::class)
            ->addTag(self::TAG_ITEM_PROVIDER);
        $container
            ->registerForAutoconfiguration(RouteInfoProviderInterface::class)
            ->addTag(self::TAG_ROUTE_INFO_PROVIDER);
        $container
            ->registerForAutoconfiguration(TitleProviderInterface::class)
            ->addTag(self::TAG_TITLE_PROVIDER);
        $container
            ->registerForAutoconfiguration(TemplateProviderInterface::class)
            ->addTag(self::TAG_TEMPLATE_PROVIDER);
        $container
            ->registerForAutoconfiguration(ItemPersisterProviderInterface::class)
            ->addTag(self::TAG_ITEM_PERSISTER_PROVIDER);
        $container
            ->registerForAutoconfiguration(IdProviderInterface::class)
            ->addTag(self::TAG_ID_PROVIDER);
        $container
            ->registerForAutoconfiguration(UrlProviderInterface::class)
            ->addTag(self::TAG_URL_PROVIDER);
        $container
            ->registerForAutoconfiguration(TranslationDomainProviderInterface::class)
            ->addTag(self::TAG_TRANSLATION_DOMAIN_PROVIDER);
        $container
            ->registerForAutoconfiguration(QueryBuilderExtensionProviderInterface::class)
            ->addTag(self::TAG_QUERY_BUILDER_EXTENSION_PROVIDER);
        $container
            ->registerForAutoconfiguration(CrudControllerInterface::class)
            ->addTag(self::TAG_CONTROLLER)
            ->addTag('controller.service_arguments')
            ->setPublic(true);
    }
}
