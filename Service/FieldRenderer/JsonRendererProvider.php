<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class JsonRendererProvider implements FieldRendererProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(FieldDefinition $fieldDefinition, $value): bool
    {
        return 'json' === $fieldDefinition->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function render(FieldDefinition $fieldDefinition, $value): string
    {
        return implode(',', $value);
    }
}
