<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Dontdrinkandroot\CrudAdminBundle\Command\InfoCommand;
use Dontdrinkandroot\CrudAdminBundle\Event\Listener\AuthorizationListener;
use Dontdrinkandroot\CrudAdminBundle\Event\Listener\DefaultRedirectAfterWriteListener;
use Dontdrinkandroot\CrudAdminBundle\Event\PostSetDataEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\PreSetDataEvent;
use Dontdrinkandroot\CrudAdminBundle\Event\RedirectAfterWriteEvent;
use Dontdrinkandroot\CrudAdminBundle\Routing\CrudRoutesLoader;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudControllerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRenderer;
use Dontdrinkandroot\CrudAdminBundle\Service\LabelService;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Dontdrinkandroot\CrudAdminBundle\Twig\CrudAdminExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
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

    $services->set(DefaultRedirectAfterWriteListener::class, DefaultRedirectAfterWriteListener::class)
        ->args([
            service(UrlResolver::class),
            service('security.authorization_checker'),
            service('translator')
        ])
        ->tag(
            'kernel.event_listener',
            ['event' => RedirectAfterWriteEvent::class, 'method' => 'onRedirectAfterWrite', 'priority' => -250]
        );

    $services->set(AuthorizationListener::class, AuthorizationListener::class)
        ->args([
            service('security.authorization_checker'),
        ])
        ->tag('kernel.event_listener', ['event' => PreSetDataEvent::class, 'method' => 'onPreSetData', 'priority' => 50]
        )
        ->tag(
            'kernel.event_listener',
            ['event' => PostSetDataEvent::class, 'method' => 'onPostSetData', 'priority' => 50]
        );

    $services->set(CrudRoutesLoader::class)
        ->args([
            service(CrudControllerRegistry::class),
            service(RouteInfoResolverInterface::class)
        ])
        ->tag('routing.loader');

    $services->set(InfoCommand::class)
        ->args([
            service(CrudControllerRegistry::class),
            service(TemplateResolverInterface::class),
            service(TranslationDomainResolverInterface::class),
            service(RouteInfoResolverInterface::class),
            service(FieldDefinitionsResolverInterface::class),
        ])
        ->tag('console.command');
};
