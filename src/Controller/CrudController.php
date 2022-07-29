<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

/**
 * @template T of object
 *
 * @extends AbstractCrudController<T>
 */
class CrudController extends AbstractCrudController
{
    /**
     * @param class-string<T> $entityClass
     */
    public function __construct(protected readonly string $entityClass)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }
}
