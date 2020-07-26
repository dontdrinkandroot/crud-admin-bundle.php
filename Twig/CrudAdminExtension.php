<?php

namespace Dontdrinkandroot\CrudAdminBundle\Twig;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CrudAdminExtension extends AbstractExtension
{
    private PropertyAccessor $propertyAccessor;

    public function __construct(PropertyAccessor $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter(
                'ddrCrudAdminFieldDefinitionValue',
                [$this, 'renderFieldDefinitionValue'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'ddrCrudAdminId',
                [$this, 'getId'],
            )
        ];
    }

    public function renderFieldDefinitionValue(object $entity, FieldDefinition $fieldDefinition)
    {
        $value = $this->propertyAccessor->getValue($entity, $fieldDefinition->getPropertyPath());
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return $value;
    }

    public function getId(object $entity)
    {
        return $this->propertyAccessor->getValue($entity, 'id');
    }
}
