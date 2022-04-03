<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface FieldDefinitionsProviderInterface extends ProviderInterface
{
    public function supportsFieldDefinitions(CrudAdminContext $context): bool;

    /**
     * @param CrudAdminContext $context
     *
     * @return FieldDefinition[]|null
     */
    public function provideFieldDefinitions(CrudAdminContext $context): ?array;
}
