<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\DdrCrudAdminExtension;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\ArrayRendererProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\DateRendererProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\DateTimeRendererProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\DecimalRendererProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRenderer;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\JsonRendererProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\NullRendererProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\TextRendererProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\ToStringRendererProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(FieldRenderer::class)
        ->args([
            tagged_iterator(DdrCrudAdminExtension::TAG_FIELD_RENDERER_PROVIDER),
        ]);

    $services->set(NullRendererProvider::class)
        ->tag(
            DdrCrudAdminExtension::TAG_FIELD_RENDERER_PROVIDER,
            ['priority' => DdrCrudAdminExtension::PRIORITY_HIGH]
        );

    $services->set(DateTimeRendererProvider::class)
        ->tag(DdrCrudAdminExtension::TAG_FIELD_RENDERER_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_MEDIUM]
        );

    $services->set(ArrayRendererProvider::class)
        ->tag(DdrCrudAdminExtension::TAG_FIELD_RENDERER_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_MEDIUM]
        );

    $services->set(JsonRendererProvider::class)
        ->tag(DdrCrudAdminExtension::TAG_FIELD_RENDERER_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_MEDIUM]
        );

    $services->set(DateRendererProvider::class)
        ->tag(DdrCrudAdminExtension::TAG_FIELD_RENDERER_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_MEDIUM]
        );

    $services->set(DecimalRendererProvider::class)
        ->tag(DdrCrudAdminExtension::TAG_FIELD_RENDERER_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_MEDIUM]
        );

    $services->set(TextRendererProvider::class)
        ->tag(DdrCrudAdminExtension::TAG_FIELD_RENDERER_PROVIDER, ['priority' => DdrCrudAdminExtension::PRIORITY_MEDIUM]
        );

    $services->set(ToStringRendererProvider::class)
        ->tag(
            DdrCrudAdminExtension::TAG_FIELD_RENDERER_PROVIDER,
            ['priority' => DdrCrudAdminExtension::PRIORITY_LOW]
        );
};
