<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface FieldRendererProviderInterface extends ProviderInterface
{
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool;

    public function render(FieldDefinition $fieldDefinition, mixed $value): string;
}
