<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Symfony\Component\Form\FormTypeInterface;

interface FormTypeProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return class-string<FormTypeInterface>
     */
    public function provideFormType(string $entityClass): ?string;
}
