<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\Form\FormInterface;

interface FormProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation   $crudOperation
     * @param T|null          $entity
     *
     * @return bool
     */
    public function supportsForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): bool;

    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation   $crudOperation
     * @param T|null          $entity
     *
     * @return FormInterface
     */
    public function provideForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): FormInterface;
}
