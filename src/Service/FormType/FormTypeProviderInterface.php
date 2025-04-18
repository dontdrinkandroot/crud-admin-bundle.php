<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Symfony\Component\Form\FormTypeInterface;

/**
 * @template T of object
 */
interface FormTypeProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return class-string<FormTypeInterface<T>>|null
     */
    public function provideFormType(string $entityClass): ?string;
}
