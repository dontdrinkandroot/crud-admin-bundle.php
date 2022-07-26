<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\Form\FormTypeInterface;

interface FormTypeProviderInterface
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return bool
     */
    public function supportsFormType(CrudOperation $crudOperation, string $entityClass, ?object $entity): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return class-string<FormTypeInterface>
     */
    public function provideFormType(CrudOperation $crudOperation, string $entityClass, ?object $entity): string;
}
