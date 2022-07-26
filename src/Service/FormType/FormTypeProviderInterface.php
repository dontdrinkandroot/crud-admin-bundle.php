<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Symfony\Component\Form\FormTypeInterface;

interface FormTypeProviderInterface
{
    /**
     * @template T of object
     *
     * @param CrudOperation $crudOperation
     * @param class-string  $entityClass
     * @param T|null        $entity
     *
     * @return class-string<FormTypeInterface>
     * @throws UnsupportedByProviderException
     */
    public function provideFormType(CrudOperation $crudOperation, string $entityClass, ?object $entity): string;
}
