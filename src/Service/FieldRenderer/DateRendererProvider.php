<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DateRendererProvider implements FieldRendererProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return Types::DATE_MUTABLE === $fieldDefinition->type
            || Types::DATE_IMMUTABLE === $fieldDefinition->type;
    }

    /**
     * {@inheritdoc}
     */
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return FieldRenderer::escapeHtml(
            Asserted::instanceOf($value, DateTimeInterface::class)->format('Y-m-d')
        );
    }
}
