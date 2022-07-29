<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\Config\DefaultSortConfig;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Sort\DefaultSortProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @template T of object
 *
 * @extends AbstractCrudController<T>
 */
abstract class AbstractProvidingCrudController extends AbstractCrudController
    implements TitleProviderInterface, RouteInfoProviderInterface,
               FormTypeProviderInterface, IdProviderInterface, ItemProviderInterface, TemplateProviderInterface,
               FieldDefinitionsProviderInterface, DefaultSortProviderInterface
{
    /**
     * {@inheritdoc}
     * @final
     */
    public function provideTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): string
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation, $entity);
        }

        return $this->getTitle($crudOperation, $entity);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param object|null   $entity
     *
     * @return string
     * @throws UnsupportedByProviderException
     */
    protected function getTitle(CrudOperation $crudOperation, ?object $entity): string
    {
        throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation, $entity);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): RouteInfo
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        return $this->getRouteInfo($crudOperation);
    }

    protected function getRouteInfo(CrudOperation $crudOperation): RouteInfo
    {
        throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideFormType(string $entityClass): string
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($entityClass);
        }

        return $this->getFormType();
    }

    /**
     * @return class-string<FormTypeInterface>
     * @throws UnsupportedByProviderException
     */
    protected function getFormType(): string
    {
        throw new UnsupportedByProviderException($this->getEntityClass());
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation, $entity);
        }

        return $this->getId($crudOperation, $entity);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param object        $entity
     *
     * @return mixed
     * @throws UnsupportedByProviderException
     */
    protected function getId(CrudOperation $crudOperation, object $entity): mixed
    {
        throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation, $entity);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideItem(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        return $this->getItem($crudOperation, $id);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param mixed         $id
     *
     * @return T|null
     * @throws UnsupportedByProviderException
     */
    public function getItem(CrudOperation $crudOperation, mixed $id): ?object
    {
        throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): string
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        return $this->getTemplate($crudOperation);
    }

    /**
     * @param CrudOperation $crudOperation
     *
     * @return string
     * @throws UnsupportedByProviderException
     */
    public function getTemplate(CrudOperation $crudOperation): string
    {
        throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideFieldDefinitions(string $entityClass, CrudOperation $crudOperation): array
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        return $this->getFieldDefinitions($crudOperation);
    }

    /**
     * @param CrudOperation $crudOperation
     *
     * @return list<FieldDefinition>
     * @throws UnsupportedByProviderException
     */
    public function getFieldDefinitions(CrudOperation $crudOperation): array
    {
        throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideDefaultSort(string $entityClass): ?DefaultSortConfig
    {
        if (!$this->matches($entityClass)) {
            return null;
        }

        return $this->getDefaultSort($entityClass);
    }

    /**
     * @param class-string $entityClass
     */
    public function getDefaultSort(string $entityClass): ?DefaultSortConfig
    {
        return null;
    }
}
