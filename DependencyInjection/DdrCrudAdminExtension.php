<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Dontdrinkandroot\CrudAdminBundle\Service\Collection\CollectionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
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
            ->registerForAutoconfiguration(CollectionProviderInterface::class)
            ->addTag('ddr_crud_admin.collection_provider');
        $container
            ->registerForAutoconfiguration(FieldDefinitionProviderInterface::class)
            ->addTag('ddr_crud_admin.field_definition_provider');
        $container
            ->registerForAutoconfiguration(FormProviderInterface::class)
            ->addTag('ddr_crud_admin.form_provider');
        $container
            ->registerForAutoconfiguration(ItemProviderInterface::class)
            ->addTag('ddr_crud_admin.item_provider');
        $container
            ->registerForAutoconfiguration(RoutesProviderInterface::class)
            ->addTag('ddr_crud_admin.route_provider');
        $container
            ->registerForAutoconfiguration(TitleProviderInterface::class)
            ->addTag('ddr_crud_admin.title_provider');
        $container
            ->registerForAutoconfiguration(TemplateProviderInterface::class)
            ->addTag('ddr_crud_admin.template_provider');

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
