<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ToStringRendererProvider implements FieldRendererProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(FieldDefinition $fieldDefinition, $value): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function render(FieldDefinition $fieldDefinition, $value): string
    {
        return FieldRenderer::escapeHtml((string)$value);
    }
}
