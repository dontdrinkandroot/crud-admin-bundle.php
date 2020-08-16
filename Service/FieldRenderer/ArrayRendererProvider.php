<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ArrayRendererProvider implements FieldRendererProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(FieldDefinition $fieldDefinition, $value): bool
    {
        return 'array' === $fieldDefinition->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function render(FieldDefinition $fieldDefinition, $value): string
    {
        return FieldRenderer::escapeHtml(implode(',', $value));
    }
}
