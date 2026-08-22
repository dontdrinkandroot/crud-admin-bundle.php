<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\Form\FormInterface;

/**
 * Resolves the form of an entity class for a CRUD operation.
 */
interface FormResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param T|null $entity
     * @return FormInterface<T>|null
     */
    public function resolveForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): ?FormInterface;
}
