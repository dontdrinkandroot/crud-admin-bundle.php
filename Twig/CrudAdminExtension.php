<?php

namespace Dontdrinkandroot\CrudAdminBundle\Twig;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CrudAdminExtension extends AbstractExtension
{
    private PropertyAccessor $propertyAccessor;

    private IdResolver $idResolver;

    public function __construct(PropertyAccessor $propertyAccessor, IdResolver $idResolver)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->idResolver = $idResolver;
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
        $id = $this->idResolver->resolve($entity);
        assert(null !== $id);

        return $id;
    }
}
