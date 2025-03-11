<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

interface FieldRendererProviderInterface
{
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool;

    public function render(FieldDefinition $fieldDefinition, mixed $value): string;
}
