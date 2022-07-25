<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\Form\FormInterface;

class PostProcessFormEvent
{
    /**
     * @template T
     *
     * @param CrudOperation        $crudOperation
     * @param class-string<T>        $entityClass
     * @param FormInterface $form
     * @param T $entity
     */
    public function __construct(
        public readonly CrudOperation $crudOperation,
        public readonly string $entityClass,
        public readonly FormInterface $form,
        public readonly object $entity
    ) {
    }
}
