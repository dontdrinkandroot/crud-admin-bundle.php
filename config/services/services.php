<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRenderer;
use Dontdrinkandroot\CrudAdminBundle\Service\LabelService;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Dontdrinkandroot\CrudAdminBundle\Twig\CrudAdminExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set(LabelService::class, LabelService::class)
        ->args([param('ddr.crud_admin.field_definition.humanize')]);

    $services->set(CrudAdminExtension::class, CrudAdminExtension::class)
        ->args([
            service('property_accessor'),
            service(FieldRenderer::class),
            service(UrlResolver::class),
            service(TitleResolver::class),
            service(TranslationDomainResolverInterface::class),
            service(FieldDefinitionsResolverInterface::class),
            service(LabelService::class)
        ])
        ->tag('twig.extension');
};
