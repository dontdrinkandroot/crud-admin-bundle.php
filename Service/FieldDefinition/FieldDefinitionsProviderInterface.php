<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface FieldDefinitionsProviderInterface extends CrudAdminProviderInterface
{
    /**
     * @param CrudAdminContext $context
     *
     * @return FieldDefinition[]|null
     */
    public function provideFieldDefinitions(CrudAdminContext $context): ?array;
}