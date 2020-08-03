<?php

namespace Dontdrinkandroot\CrudAdminBundle\Twig;

use DateTimeInterface;
use Doctrine\Common\Util\ClassUtils;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRenderer;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CrudAdminExtension extends AbstractExtension
{
    private PropertyAccessor $propertyAccessor;

    private UrlResolver $urlResolver;

    private RequestStack $requestStack;

    private FieldRenderer $fieldRenderer;

    public function __construct(
        PropertyAccessor $propertyAccessor,
        UrlResolver $urlResolver,
        RequestStack $requestStack,
        FieldRenderer $fieldRenderer
    ) {
        $this->propertyAccessor = $propertyAccessor;
        $this->urlResolver = $urlResolver;
        $this->requestStack = $requestStack;
        $this->fieldRenderer = $fieldRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('ddrCrudAdminPath', [$this, 'getUrl']),
        ];
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

    public function getUrl(string $crudOperation, ?object $entity = null): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        assert(null !== $request);
        $entityClass = RequestAttributes::getEntityClass($request);
        if (null !== $entity) {
            $entityClass = get_class($entity);
            if (class_exists('Doctrine\Common\Util\ClassUtils')) {
                $entityClass = ClassUtils::getRealClass($entityClass);
            }
        }

        $context = new CrudAdminContext($entityClass, $crudOperation, $request);
        if (null !== $entity) {
            $context->setEntity($entity);
            $context->setEntityResolved(true);
        }
        return $this->urlResolver->resolve($context);
    }
}
