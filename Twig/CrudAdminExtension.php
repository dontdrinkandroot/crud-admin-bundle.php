<?php

namespace Dontdrinkandroot\CrudAdminBundle\Twig;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Symfony\Component\HttpFoundation\RequestStack;
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

    private UrlResolver $urlResolver;

    private RequestStack $requestStack;

    public function __construct(PropertyAccessor $propertyAccessor, IdResolver $idResolver, UrlResolver $urlResolver, RequestStack  $requestStack)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->idResolver = $idResolver;
        $this->urlResolver = $urlResolver;
        $this->requestStack = $requestStack;
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
                [$this, 'getUrl'],
            )
        ];
    }

    public function renderFieldDefinitionValue(object $entity, FieldDefinition $fieldDefinition)
    {
        $value = $this->propertyAccessor->getValue($entity, $fieldDefinition->getPropertyPath());
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return $value;
    }

    public function getUrl($entityOrClass, string $crudOperation): ?string
    {
        return $this->urlResolver->resolve($entityOrClass, $crudOperation, $this->requestStack->getCurrentRequest());
    }
}
