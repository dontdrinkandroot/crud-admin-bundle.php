<?php

namespace Dontdrinkandroot\CrudAdminBundle\Serializer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class FieldDefinitionNormalizer implements ContextAwareDenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = [])
    {
        return FieldDefinition::class === $type;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new FieldDefinition(
            propertyPath: $data['property_path'],
            type: $data['type'],
            sortable: $data['sortable'] ?? false,
            filterable: $data['filterable'] ?? false
        );
    }
}
