<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class RoutesResolverCompilerPass extends AbstractProviderServiceCompilerPass
{

    /**
     * {@inheritdoc}
     */
    protected function getProviderServiceClass(): string
    {
        return RoutesResolver::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTagName(): string
    {
        return 'ddr_crud_admin.route_provider';
    }
}
