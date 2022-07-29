<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\Serializer\CrudConfigDenormalizer;
use Dontdrinkandroot\CrudAdminBundle\Serializer\FieldDefinitionDenormalizer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set(CrudConfigDenormalizer::class)
        ->args([service('serializer.normalizer.object')])
        ->tag('serializer.normalizer');

    $services->set(FieldDefinitionDenormalizer::class)
        ->tag('serializer.normalizer');
};
