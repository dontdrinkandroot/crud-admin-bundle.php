<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Symfony\Component\Form\FormTypeInterface;

interface FormTypeProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return class-string<FormTypeInterface>
     * @throws UnsupportedByProviderException
     */
    public function provideFormType(string $entityClass): string;
}
