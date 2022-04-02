<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Exception\NoRendererFoundException;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use RuntimeException;

class FieldRenderer extends AbstractProviderService
{
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof FieldRendererProviderInterface);
            if ($provider->supports($fieldDefinition, $value)) {
                return $provider->render($fieldDefinition, $value);
            }
        }

        throw new NoRendererFoundException(
            sprintf(
                'Cannot render FieldDefinitionType "%s" with value "%s"',
                $fieldDefinition->getType(),
                $value
            )
        );
    }

    public static function escapeHtml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
