<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\DoctrineFieldDefinitionsProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\DoctrineFormProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\DoctrineIdProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\DoctrineItemProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\LabelService;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\DoctrinePaginationTargetProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\DoctrineItemPersisterProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set(DoctrineItemProvider::class)
        ->args([service('doctrine')])
        ->tag(DdrCrudAdminExtension::TAG_ITEM_PROVIDER, ['priority' => -200]);

    $services->set(DoctrinePaginationTargetProvider::class)
        ->args([
            service('doctrine'),
            tagged_iterator(DdrCrudAdminExtension::TAG_QUERY_BUILDER_EXTENSION_PROVIDER),
            tagged_iterator(DdrCrudAdminExtension::TAG_QUERY_EXTENSION_PROVIDER)
        ])
        ->tag(DdrCrudAdminExtension::TAG_PAGINATION_TARGET_PROVIDER, ['priority' => -200]);

    $services->set(DoctrineFieldDefinitionsProvider::class)
        ->args([service('doctrine'),])
        ->tag(DdrCrudAdminExtension::TAG_FIELD_DEFINITIONS_PROVIDER, ['priority' => -200]);

    $services->set(DoctrineFormProvider::class)
        ->args([
            service('doctrine'),
            service('form.factory'),
            service(TranslationDomainResolverInterface::class),
            service(LabelService::class)
        ])
        ->tag(DdrCrudAdminExtension::TAG_FORM_PROVIDER, ['priority' => -200]);

    $services->set(DoctrineItemPersisterProvider::class)
        ->args([service('doctrine'),])
        ->tag(DdrCrudAdminExtension::TAG_ITEM_PERSISTER_PROVIDER, ['priority' => -200]);

    $services->set(DoctrineIdProvider::class)
        ->args([
            service('doctrine'),
            service('property_accessor'),
        ])
        ->tag(DdrCrudAdminExtension::TAG_ID_PROVIDER, ['priority' => -200]);
};
