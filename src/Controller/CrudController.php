<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Override;

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

    #[Override]
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }
}
