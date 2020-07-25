<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitionProvider;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface FieldDefinitionProviderInterface extends ProviderInterface
{
    /**
     * @param CrudAdminRequest $request
     *
     * @return FieldDefinition[]|null
     */
    public function provideFieldDefinitions(CrudAdminRequest $request): ?array;
}
