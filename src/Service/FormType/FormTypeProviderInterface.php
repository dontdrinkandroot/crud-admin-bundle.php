<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @template T of object
 */
interface FormTypeProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return class-string<FormTypeInterface<T>>|null
     */
    public function provideFormType(string $entityClass): ?string;
}
