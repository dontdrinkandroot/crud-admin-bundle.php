<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use DateTimeInterface;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DateRendererProvider implements FieldRendererProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(FieldDefinition $fieldDefinition, $value): bool
    {
        return 'date' === $fieldDefinition->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function render(FieldDefinition $fieldDefinition, $value): string
    {
        assert($value instanceof DateTimeInterface);
        return FieldRenderer::escapeHtml($value->format('Y-m-d'));
    }
}
