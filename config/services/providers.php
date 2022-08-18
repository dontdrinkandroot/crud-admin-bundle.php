<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\DefaultTitleProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set(DefaultTitleProvider::class, DefaultTitleProvider::class)
        ->args([
            service(TranslationDomainResolverInterface::class),
            service('translator'),
            param('ddr.crud_admin.field_definition.title_type')
        ])
        ->tag(DdrCrudAdminExtension::TAG_TITLE_PROVIDER, ['priority' => -250]);
};
