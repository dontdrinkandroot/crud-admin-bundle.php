<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\CachedFieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\CachedRouteInfoResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\CachedTemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\CachedTranslationDomainResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(TitleResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_TITLE_PROVIDER),
        ]);

    $services->set(FieldDefinitionsResolverInterface::class, CachedFieldDefinitionsResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_FIELD_DEFINITIONS_PROVIDER),
            service('cache.system')
        ]);

    $services->set(PaginationResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_PAGINATION_PROVIDER),
        ]);

    $services->set(PaginationTargetResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_PAGINATION_TARGET_PROVIDER),
        ]);

    $services->set(ItemResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_ITEM_PROVIDER),
        ]);

    $services->set(RouteInfoResolverInterface::class, CachedRouteInfoResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_ROUTE_INFO_PROVIDER),
            service('cache.system')
        ]);

    $services->set(TemplateResolverInterface::class, CachedTemplateResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_TEMPLATE_PROVIDER),
            service('cache.system')
        ]);

    $services->set(FormTypeResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_FORM_TYPE_PROVIDER),
        ]);

    $services->set(FormResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_FORM_PROVIDER),
        ]);

    $services->set(ItemPersister::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_ITEM_PERSISTER_PROVIDER),
        ]);

    $services->set(IdResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_ID_PROVIDER),
        ]);

    $services->set(UrlResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_URL_PROVIDER),
        ]);

    $services->set(TranslationDomainResolverInterface::class, CachedTranslationDomainResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_TRANSLATION_DOMAIN_PROVIDER),
            service('cache.system')
        ]);
};
