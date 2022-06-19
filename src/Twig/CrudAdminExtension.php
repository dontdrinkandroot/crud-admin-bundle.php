<?php

namespace Dontdrinkandroot\CrudAdminBundle\Twig;

use Doctrine\Common\Util\ClassUtils;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRenderer;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CrudAdminExtension extends AbstractExtension
{
    public function __construct(
        private PropertyAccessor $propertyAccessor,
        private UrlResolver $urlResolver,
        private RequestStack $requestStack,
        private FieldRenderer $fieldRenderer,
        private TitleResolver $titleResolver
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('ddrCrudAdminPath', [$this, 'getUrl']),
            new TwigFunction('ddrCrudAdminTitle', [$this, 'getTitle']),
        ];
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
            new TwigFilter(
                'ddrCrudAdminPath',
                fn(object $entity, string $crudOperation) => $this->getUrl($crudOperation, $entity)
            )
        ];
    }

    public function renderFieldDefinitionValue(object $entity, FieldDefinition $fieldDefinition): string
    {
        $value = $this->propertyAccessor->getValue($entity, $fieldDefinition->getPropertyPath());
        return $this->fieldRenderer->render($fieldDefinition, $value);
    }

    public function getTitle(string $crudOperation, ?object $entity = null): ?string
    {
        $context = $this->buildContext($crudOperation, $entity);

        return $this->titleResolver->resolve($context);
    }

    public function getUrl(string $crudOperation, ?object $entity = null): ?string
    {
        $context = $this->buildContext($crudOperation, $entity);

        return $this->urlResolver->resolve($context);
    }

    protected function buildContext(string $crudOperation, ?object $entity): CrudAdminContext
    {
        $request = Asserted::notNull($this->requestStack->getCurrentRequest());
        $entityClass = RequestAttributes::getEntityClass($request);
        if (null !== $entity) {
            $entityClass = get_class($entity);
            if (class_exists('Doctrine\Common\Util\ClassUtils')) {
                $entityClass = ClassUtils::getRealClass($entityClass);
            }
        }

        $context = new CrudAdminContext(Asserted::notNull($entityClass), $crudOperation, $request);
        if (null !== $entity) {
            $context->setEntity($entity);
            $context->setEntityResolved(true);
        }
        return $context;
    }
}
