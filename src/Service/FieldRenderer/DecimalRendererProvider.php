<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Doctrine\DBAL\Types\Types;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Override;

class DecimalRendererProvider  implements FieldRendererProviderInterface
{
    #[Override]
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return
            Types::FLOAT === $fieldDefinition->displayType
            || Types::DECIMAL === $fieldDefinition->displayType;
    }

    #[Override]
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return number_format($value, 2);
    }
}
