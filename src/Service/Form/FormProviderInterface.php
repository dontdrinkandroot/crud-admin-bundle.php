<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\Form\FormInterface;

interface FormProviderInterface extends ProviderInterface
{
    /**
     * @param class-string $entityClass
     * @return FormInterface|null
     */
    public function provideForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?FormInterface;
}
