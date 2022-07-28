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
    public function provideTitle(CrudOperation $crudOperation, string $entityClass, ?object $entity): string
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass, $entity);
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
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass(), $entity);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideRouteInfo(CrudOperation $crudOperation, string $entityClass): RouteInfo
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
        }

        return $this->getRouteInfo($crudOperation);
    }

    protected function getRouteInfo(CrudOperation $crudOperation): RouteInfo
    {
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass());
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideFormType(CrudOperation $crudOperation, string $entityClass, ?object $entity): string
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass, $entity);
        }

        return $this->getFormType($crudOperation, $entity);
    }

    /**
     * @param CrudOperation $crudOperation
     * @param object|null   $entity
     *
     * @return class-string<FormTypeInterface>
     * @throws UnsupportedByProviderException
     */
    protected function getFormType(CrudOperation $crudOperation, ?object $entity): string
    {
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass(), $entity);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideId(CrudOperation $crudOperation, string $entityClass, object $entity): mixed
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass, $entity);
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
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass(), $entity);
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideItem(CrudOperation $crudOperation, string $entityClass, mixed $id): ?object
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
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
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass());
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideTemplate(CrudOperation $crudOperation, string $entityClass): string
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
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
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass());
    }

    /**
     * {@inheritdoc}
     * @final
     */
    public function provideFieldDefinitions(CrudOperation $crudOperation, string $entityClass): array
    {
        if (!$this->matches($entityClass)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
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
        throw new UnsupportedByProviderException($crudOperation, $this->getEntityClass());
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
