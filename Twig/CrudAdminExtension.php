<?php

namespace Dontdrinkandroot\CrudAdminBundle\Twig;

use DateTimeInterface;
use Doctrine\Common\Util\ClassUtils;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
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

    private IdResolver $idResolver;

    private UrlResolver $urlResolver;

    private RequestStack $requestStack;

    public function __construct(
        PropertyAccessor $propertyAccessor,
        IdResolver $idResolver,
        UrlResolver $urlResolver,
        RequestStack $requestStack
    ) {
        $this->propertyAccessor = $propertyAccessor;
        $this->idResolver = $idResolver;
        $this->urlResolver = $urlResolver;
        $this->requestStack = $requestStack;
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

    public function renderFieldDefinitionValue(object $entity, FieldDefinition $fieldDefinition)
    {
        $value = $this->propertyAccessor->getValue($entity, $fieldDefinition->getPropertyPath());
        if (null === $value) {
            return '<i>null</i>';
        }

        switch ($fieldDefinition->getType()) {
            case 'datetime':
                assert(
                    $value instanceof DateTimeInterface,
                    $fieldDefinition->getPropertyPath() . ' was not a DateTimeInterface'
                );

                return $value->format('Y-m-d H:i:s');
            case 'date':
                assert(
                    $value instanceof DateTimeInterface,
                    $fieldDefinition->getPropertyPath() . ' was not a DateTimeInterface'
                );

                return $value->format('Y-m-d');
            case 'boolean';
                return $value ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>';
            case 'json':
                return implode(',', $value);
        }

        $value = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return $value;
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
