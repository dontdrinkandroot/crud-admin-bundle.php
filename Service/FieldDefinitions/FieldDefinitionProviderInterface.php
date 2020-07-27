<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface FieldDefinitionProviderInterface extends ProviderInterface
{
    /**
     * @param Request $request
     *
     * @return FieldDefinition[]|null
     */
    public function provideFieldDefinitions(Request $request): ?array;
}
