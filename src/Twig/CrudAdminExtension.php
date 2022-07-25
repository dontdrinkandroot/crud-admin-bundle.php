<?php

namespace Dontdrinkandroot\CrudAdminBundle\Twig;

use Doctrine\Common\Util\ClassUtils;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRenderer;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CrudAdminExtension extends AbstractExtension
{
    public function __construct(
        private readonly PropertyAccessor $propertyAccessor,
        private readonly FieldRenderer $fieldRenderer,
        private readonly UrlResolver $urlResolver
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'ddrCrudAdminFieldDefinitionValue',
                [$this, 'renderFieldDefinitionValue'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter('ddrCrudPath', [$this, 'getPath'])
        ];
    }

    public function renderFieldDefinitionValue(object $entity, FieldDefinition $fieldDefinition): string
    {
        $value = $this->propertyAccessor->getValue($entity, $fieldDefinition->propertyPath);
        return $this->fieldRenderer->render($fieldDefinition, $value);
    }

    /**
     * @template T
     *
     * @param string            $crudOperation
     * @param class-string<T>|T $entityOrClass
     *
     * @return string|null
     */
    public function getPath(string $crudOperation, string|object $entityOrClass): ?string
    {
        $entityClass = is_object($entityOrClass) ? $this->getClass($entityOrClass) : $entityOrClass;
        $entity = is_object($entityOrClass) ? $entityOrClass : null;
        return $this->urlResolver->resolve(CrudOperation::from($crudOperation), $entityClass, $entity);
    }

    /**
     * @template T of object
     *
     * @param T $entity
     *
     * @return class-string<T>
     */
    private function getClass(object $entity): string
    {
        $entityClass = get_class($entity);
        if (class_exists('Doctrine\Common\Util\ClassUtils')) {
            return ClassUtils::getRealClass($entityClass);
        }

        return $entityClass;
    }
}
