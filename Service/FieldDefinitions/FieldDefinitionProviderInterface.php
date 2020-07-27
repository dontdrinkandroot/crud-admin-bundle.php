<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface FieldDefinitionProviderInterface extends RequestProviderInterface
{
    /**
     * @param Request $request
     *
     * @return FieldDefinition[]|null
     */
    public function provideFieldDefinitions(Request $request): ?array;
}
