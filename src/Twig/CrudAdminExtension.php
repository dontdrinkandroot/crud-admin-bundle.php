<?php

namespace Dontdrinkandroot\CrudAdminBundle\Twig;

use Doctrine\Common\Util\ClassUtils;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRenderer;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CrudAdminExtension extends AbstractExtension
{
    public function __construct(
        private readonly PropertyAccessor $propertyAccessor,
        private readonly FieldRenderer $fieldRenderer,
        private readonly UrlResolver $urlResolver,
        private readonly TitleResolver $titleResolver,
        private readonly TranslationDomainResolverInterface $translationDomainResolver,
        private readonly FieldDefinitionsResolverInterface $fieldDefinitionsResolver
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
            new TwigFilter('ddrCrudPath', [$this, 'getPath']),
            new TwigFilter('ddrCrudTitle', [$this, 'getTitle']),
            new TwigFilter('ddrCrudTranslationDomain', [$this, 'getTranslationDomain']),
            new TwigFilter('ddrCrudFieldDefinitions', [$this, 'getFieldDefinitions'])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'ddrCrudAdminFieldDefinitionValue',
                [$this, 'renderFieldDefinitionValue'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction('ddrCrudPath', [$this, 'getPath']),
            new TwigFunction('ddrCrudTitle', [$this, 'getTitle']),
            new TwigFunction('ddrCrudTranslationDomain', [$this, 'getTranslationDomain']),
            new TwigFunction('ddrCrudFieldDefinitions', [$this, 'getFieldDefinitions'])
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
     * @template T
     *
     * @param string            $crudOperation
     * @param class-string<T>|T $entityOrClass
     *
     * @return string
     */
    public function getTitle(string $crudOperation, string|object $entityOrClass): string
    {
        $entityClass = is_object($entityOrClass) ? $this->getClass($entityOrClass) : $entityOrClass;
        $entity = is_object($entityOrClass) ? $entityOrClass : null;
        return Asserted::notNull(
            $this->titleResolver->resolve(CrudOperation::from($crudOperation), $entityClass, $entity),
            sprintf("No title provided for %s::%s", $crudOperation, $entityClass)
        );
    }

    /**
     * @template T
     *
     * @param string          $crudOperation
     * @param class-string<T>|T $entityOrClass
     *
     * @return string
     */
    public function getTranslationDomain(string $crudOperation, string|object $entityOrClass): string
    {
        $entityClass = is_object($entityOrClass) ? $this->getClass($entityOrClass) : $entityOrClass;
        return Asserted::notNull(
            $this->translationDomainResolver->resolve(CrudOperation::from($crudOperation), $entityClass),
            sprintf("No translationDomain provided for %s::%s", $crudOperation, $entityClass)
        );
    }

    /**
     * @template T
     *
     * @param string          $crudOperation
     * @param class-string<T>|T $entityOrClass
     *
     * @return list<FieldDefinition>
     */
    public function getFieldDefinitions(string $crudOperation, string|object $entityOrClass): array
    {
        $entityClass = is_object($entityOrClass) ? $this->getClass($entityOrClass) : $entityOrClass;
        return Asserted::notNull(
            $this->fieldDefinitionsResolver->resolve(CrudOperation::from($crudOperation), $entityClass),
            sprintf("No fieldDefinitions provided for %s::%s", $crudOperation, $entityClass)
        );
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
