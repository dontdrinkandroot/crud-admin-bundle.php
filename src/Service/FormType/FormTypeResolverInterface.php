<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Symfony\Component\Form\FormTypeInterface;

interface FormTypeResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @return class-string<FormTypeInterface<T>>|null
     */
    public function resolveFormType(string $entityClass): ?string;
}
