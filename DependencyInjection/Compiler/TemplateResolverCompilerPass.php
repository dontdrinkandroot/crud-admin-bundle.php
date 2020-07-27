<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

use Dontdrinkandroot\CrudAdminBundle\Service\Collection\CollectionResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TemplateResolverCompilerPass extends AbstractProviderServiceCompilerPass
{
    /**
     * {@inheritdoc}
     */
    protected function getProviderServiceClass(): string
    {
        return TemplateResolver::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTagName(): string
    {
        return 'ddr_crud_admin.template_provider';
    }
}