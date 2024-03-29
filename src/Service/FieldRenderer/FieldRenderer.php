<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Exception\NoRendererFoundException;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<FieldRendererProviderInterface>
 */
class FieldRenderer extends AbstractProviderService
{
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($fieldDefinition, $value)) {
                return $provider->render($fieldDefinition, $value);
            }
        }

        throw new NoRendererFoundException(
            sprintf(
                'Cannot render FieldDefinitionType "%s" with value "%s"',
                $fieldDefinition->displayType,
                $value
            )
        );
    }

    public static function escapeHtml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
