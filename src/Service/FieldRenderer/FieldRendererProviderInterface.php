<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

/**
 * Renders the value of a field definition.
 */
interface FieldRendererProviderInterface
{
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool;

    public function render(FieldDefinition $fieldDefinition, mixed $value): string;
}
