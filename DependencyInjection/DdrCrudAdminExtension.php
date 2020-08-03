<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRendererProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\NewInstance\NewInstanceProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersisterProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplatesProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DdrCrudAdminExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container
            ->registerForAutoconfiguration(FieldRendererProviderInterface::class)
            ->addTag('ddr_crud_admin.field_renderer_provider');
        $container
            ->registerForAutoconfiguration(PaginationProviderInterface::class)
            ->addTag('ddr_crud_admin.pagination_provider');
        $container
            ->registerForAutoconfiguration(PaginationTargetProvider::class)
            ->addTag('ddr_crud_admin.pagination_target_provider');
        $container
            ->registerForAutoconfiguration(FieldDefinitionsProviderInterface::class)
            ->addTag('ddr_crud_admin.field_definitions_provider');
        $container
            ->registerForAutoconfiguration(FormProviderInterface::class)
            ->addTag('ddr_crud_admin.form_provider');
        $container
            ->registerForAutoconfiguration(ItemProviderInterface::class)
            ->addTag('ddr_crud_admin.item_provider');
        $container
            ->registerForAutoconfiguration(RoutesProviderInterface::class)
            ->addTag('ddr_crud_admin.routes_provider');
        $container
            ->registerForAutoconfiguration(TitleProviderInterface::class)
            ->addTag('ddr_crud_admin.title_provider');
        $container
            ->registerForAutoconfiguration(TemplatesProviderInterface::class)
            ->addTag('ddr_crud_admin.templates_provider');
        $container
            ->registerForAutoconfiguration(ItemPersisterProviderInterface::class)
            ->addTag('ddr_crud_admin.item_persister_provider');
        $container
            ->registerForAutoconfiguration(IdProviderInterface::class)
            ->addTag('ddr_crud_admin.id_provider');
        $container
            ->registerForAutoconfiguration(NewInstanceProviderInterface::class)
            ->addTag('ddr_crud_admin.new_instance_provider');
        $container
            ->registerForAutoconfiguration(UrlProviderInterface::class)
            ->addTag('ddr_crud_admin.url_provider');
        $container
            ->registerForAutoconfiguration(TranslationDomainProviderInterface::class)
            ->addTag('ddr_crud_admin.translation_domain_provider');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/services'));
        $loader->load('services.yaml');

        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('DoctrineBundle', $bundles)) {
            $loader->load('doctrine.yaml');
        }
    }
}
