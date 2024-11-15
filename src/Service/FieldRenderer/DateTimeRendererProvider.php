<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Override;

class DateTimeRendererProvider implements FieldRendererProviderInterface
{
    #[Override]
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return Types::DATETIME_MUTABLE === $fieldDefinition->displayType
            || Types::DATETIME_IMMUTABLE === $fieldDefinition->displayType;
    }

    #[Override]
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return FieldRenderer::escapeHtml(
            Asserted::instanceOf($value, DateTimeInterface::class)->format('Y-m-d H:i:s')
        );
    }
}
