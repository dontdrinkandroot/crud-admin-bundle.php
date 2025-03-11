<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\CachedFieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;
use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersisterInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\CachedRouteInfoResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\CachedTemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\CachedTranslationDomainResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(TitleResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_TITLE_PROVIDER),
        ]);
    $services->alias(TitleResolverInterface::class, TitleResolver::class);

    $services->set(CachedFieldDefinitionsResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_FIELD_DEFINITIONS_PROVIDER),
            service('cache.system')
        ]);
    $services->alias(FieldDefinitionsResolverInterface::class, CachedFieldDefinitionsResolver::class);

    $services->set(PaginationResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_PAGINATION_PROVIDER),
        ]);
    $services->alias(PaginationResolverInterface::class, PaginationResolver::class);

    $services->set(PaginationTargetResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_PAGINATION_TARGET_PROVIDER),
        ]);
    $services->alias(PaginationTargetResolverInterface::class, PaginationTargetResolver::class);

    $services->set(ItemResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_ITEM_PROVIDER),
        ]);
    $services->alias(ItemResolverInterface::class, ItemResolver::class);

    $services->set(CachedRouteInfoResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_ROUTE_INFO_PROVIDER),
            service('cache.system')
        ]);
    $services->alias(RouteInfoResolverInterface::class, CachedRouteInfoResolver::class);

    $services->set(CachedTemplateResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_TEMPLATE_PROVIDER),
            service('cache.system')
        ]);
    $services->alias(TemplateResolverInterface::class, CachedTemplateResolver::class);

    $services->set(FormTypeResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_FORM_TYPE_PROVIDER),
        ]);
    $services->alias(FormTypeResolverInterface::class, FormTypeResolver::class);

    $services->set(FormResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_FORM_PROVIDER),
        ]);
    $services->alias(FormResolverInterface::class, FormResolver::class);

    $services->set(ItemPersister::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_ITEM_PERSISTER_PROVIDER),
        ]);
    $services->alias(ItemPersisterInterface::class, ItemPersister::class);

    $services->set(IdResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_ID_PROVIDER),
        ]);
    $services->alias(IdResolverInterface::class, IdResolver::class);

    $services->set(UrlResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_URL_PROVIDER),
        ]);
    $services->alias(UrlResolverInterface::class, UrlResolver::class);

    $services->set(TranslationDomainResolverInterface::class, CachedTranslationDomainResolver::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_TRANSLATION_DOMAIN_PROVIDER),
            service('cache.system')
        ]);
    $services->alias(CachedTranslationDomainResolver::class, TranslationDomainResolverInterface::class);
};
