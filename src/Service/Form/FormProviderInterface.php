<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @template T of object
 */
interface FormProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return FormInterface<T>|null
     */
    public function provideForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?FormInterface;
}
