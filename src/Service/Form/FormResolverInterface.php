<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\Form\FormInterface;

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
