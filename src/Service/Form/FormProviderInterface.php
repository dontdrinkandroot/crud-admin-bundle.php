<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\Form\FormInterface;

interface FormProviderInterface extends ProviderInterface
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
    public function supportsForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return ?FormInterface
     */
    public function provideForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): ?FormInterface;
}
