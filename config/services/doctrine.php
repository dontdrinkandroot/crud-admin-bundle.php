<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\DoctrineFieldDefinitionsProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\DoctrineIdProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\DoctrineItemProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\DoctrinePaginationTargetProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\DoctrineItemPersisterProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(DoctrineItemProvider::class)
        ->args([service('doctrine')])
        ->tag(DdrCrudAdminExtension::TAG_ITEM_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]);

    $services->set(DoctrinePaginationTargetProvider::class)
        ->args([
            service('doctrine'),
            tagged_iterator(DdrCrudAdminExtension::TAG_QUERY_BUILDER_EXTENSION_PROVIDER),
            tagged_iterator(DdrCrudAdminExtension::TAG_QUERY_EXTENSION_PROVIDER)
        ])
        ->tag(DdrCrudAdminExtension::TAG_PAGINATION_TARGET_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]
        );

    $services->set(DoctrineFieldDefinitionsProvider::class)
        ->args([service('doctrine')])
        ->tag(DdrCrudAdminExtension::TAG_FIELD_DEFINITIONS_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]
        );

    $services->set(DoctrineItemPersisterProvider::class)
        ->args([service('doctrine'),])
        ->tag(DdrCrudAdminExtension::TAG_ITEM_PERSISTER_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]);

    $services->set(DoctrineIdProvider::class)
        ->args([
            service('doctrine'),
            service('property_accessor'),
        ])
        ->tag(DdrCrudAdminExtension::TAG_ID_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]);
};
