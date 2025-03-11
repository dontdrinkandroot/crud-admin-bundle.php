<?php

namespace Dontdrinkandroot\CrudAdminBundle\Twig;

use Doctrine\Common\Util\ClassUtils;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRenderer;
use Dontdrinkandroot\CrudAdminBundle\Service\LabelService;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolverInterface;
use Override;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CrudAdminExtension extends AbstractExtension
{
    public function __construct(
        private readonly PropertyAccessor $propertyAccessor,
        private readonly FieldRenderer $fieldRenderer,
        private readonly UrlResolverInterface $urlResolver,
        private readonly TitleResolverInterface $titleResolver,
        private readonly TranslationDomainResolverInterface $translationDomainResolver,
        private readonly FieldDefinitionsResolverInterface $fieldDefinitionsResolver,
        private readonly LabelService $labelService
    ) {
    }

    #[Override]
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'ddrCrudAdminFieldDefinitionValue',
                $this->renderFieldDefinitionValue(...),
                ['is_safe' => ['html']]
            ),
            new TwigFilter('ddrCrudAdminLabel', $this->getLabel(...))
        ];
    }

    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'ddrCrudAdminFieldDefinitionValue',
                $this->renderFieldDefinitionValue(...),
                ['is_safe' => ['html']]
            ),
            new TwigFunction('ddrCrudAdminPath', $this->getPath(...)),
            new TwigFunction('ddrCrudAdminTitle', $this->getTitle(...)),
            new TwigFunction('ddrCrudAdminTranslationDomain', $this->getTranslationDomain(...)),
            new TwigFunction('ddrCrudAdminFieldDefinitions', $this->getFieldDefinitions(...)),
            new TwigFunction('ddrCrudAdminLabel', $this->getLabel(...))
        ];
    }

    public function renderFieldDefinitionValue(object $entity, FieldDefinition $fieldDefinition): string
    {
        $value = $this->propertyAccessor->getValue($entity, $fieldDefinition->propertyPath);
        return $this->fieldRenderer->render($fieldDefinition, $value);
    }

    /**
     * @template T of object
     *
     * @param class-string<T>|T $entityOrClass
     *
     */
    public function getPath(string|object $entityOrClass, string $crudOperation): ?string
    {
        $entityClass = is_object($entityOrClass) ? $this->getClass($entityOrClass) : $entityOrClass;
        $entity = is_object($entityOrClass) ? $entityOrClass : null;
        return $this->urlResolver->resolveUrl($entityClass, CrudOperation::from($crudOperation), $entity);
    }

    /**
     * @template T of object
     *
     * @param class-string<T>|T $entityOrClass
     *
     */
    public function getTitle(string|object $entityOrClass, string $crudOperation): string
    {
        $entityClass = is_object($entityOrClass) ? $this->getClass($entityOrClass) : $entityOrClass;
        $entity = is_object($entityOrClass) ? $entityOrClass : null;
        return Asserted::notNull(
            $this->titleResolver->resolveTitle($entityClass, CrudOperation::from($crudOperation), $entity),
            sprintf("No title provided for %s::%s", $crudOperation, $entityClass)
        );
    }

    /**
     * @template T of object
     *
     * @param class-string<T>|T $entityOrClass
     */
    public function getTranslationDomain(string|object $entityOrClass): string
    {
        $entityClass = is_object($entityOrClass) ? $this->getClass($entityOrClass) : $entityOrClass;
        return Asserted::notNull(
            $this->translationDomainResolver->resolveTranslationDomain($entityClass),
            sprintf("No translationDomain provided for %s", $entityClass)
        );
    }

    /**
     * @template T of object
     *
     * @param class-string<T>|T $entityOrClass
     *
     * @return FieldDefinition[]
     */
    public function getFieldDefinitions(string|object $entityOrClass, string $crudOperation): array
    {
        $entityClass = is_object($entityOrClass) ? $this->getClass($entityOrClass) : $entityOrClass;
        return Asserted::notNull(
            $this->fieldDefinitionsResolver->resolveFieldDefinitions($entityClass, CrudOperation::from($crudOperation)),
            sprintf("No fieldDefinitions provided for %s::%s", $crudOperation, $entityClass)
        );
    }

    public function getLabel(FieldDefinition|string $value): string
    {
        return $this->labelService->getLabel($value);
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
        $entityClass = $entity::class;
        if (class_exists(ClassUtils::class)) {
            return ClassUtils::getRealClass($entityClass);
        }

        return $entityClass;
    }
}
