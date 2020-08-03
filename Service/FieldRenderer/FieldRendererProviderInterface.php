<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface FieldRendererProviderInterface extends ProviderInterface
{
    public function supports(FieldDefinition $fieldDefinition, $value): bool;

    public function render(FieldDefinition $fieldDefinition, $value): string;
}
