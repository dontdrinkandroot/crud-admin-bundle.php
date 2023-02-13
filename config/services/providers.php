<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FieldDefinitionsFormProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormTypeFormProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\LabelService;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\DefaultPaginationProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\DefaultRouteInfoProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\DefaultTemplateProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\DefaultTitleProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\ToStringTitleProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\DefaultTranslationDomainProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\DefaultUrlProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(ToStringTitleProvider::class)
        ->tag(DdrCrudAdminExtension::TAG_TITLE_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_MEDIUM]);

    $services->set(DefaultTitleProvider::class, DefaultTitleProvider::class)
        ->args([
            service(TranslationDomainResolverInterface::class),
            service('translator'),
            param('ddr.crud_admin.field_definition.title_type')
        ])
        ->tag(DdrCrudAdminExtension::TAG_TITLE_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]);

    $services->set(DefaultPaginationProvider::class)
        ->args([
            service('knp_paginator'),
            service(PaginationTargetResolver::class),
            service(FieldDefinitionsResolverInterface::class),
            service('request_stack'),
            tagged_iterator(DdrCrudAdminExtension::TAG_DEFAULT_SORT_PROVIDER),
        ])
        ->tag(DdrCrudAdminExtension::TAG_PAGINATION_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]);

    $services->set(DefaultTemplateProvider::class)
        ->tag(DdrCrudAdminExtension::TAG_TEMPLATE_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]);

    $services->set(DefaultRouteInfoProvider::class)
        ->tag(DdrCrudAdminExtension::TAG_ROUTE_INFO_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]);

    $services->set(DefaultUrlProvider::class)
        ->args([
            service(RouteInfoResolverInterface::class),
            service('router'),
            service(IdResolver::class)
        ])
        ->tag(DdrCrudAdminExtension::TAG_URL_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]);

    $services->set(FieldDefinitionsFormProvider::class)
        ->args([
            service('form.factory'),
            service(TranslationDomainResolverInterface::class),
            service(LabelService::class),
            service(FieldDefinitionsResolverInterface::class)
        ])
        ->tag(DdrCrudAdminExtension::TAG_FORM_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]);

    $services->set(FormTypeFormProvider::class)
        ->args([
            service('form.factory'),
            service(FormTypeResolver::class)
        ])
        ->tag(DdrCrudAdminExtension::TAG_FORM_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_MEDIUM]);

    $services->set(DefaultTranslationDomainProvider::class)
        ->tag(
            DdrCrudAdminExtension::TAG_TRANSLATION_DOMAIN_PROVIDER,
            ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]
        );
};

