<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class PostProcessFormEvent
{
    /**
     * @template T
     *
     * @param string        $crudOperation
     * @param class-string<T>        $entityClass
     * @param FormInterface $form
     * @param T $entity
     */
    public function __construct(
        public readonly string $crudOperation,
        public readonly string $entityClass,
        public readonly FormInterface $form,
        public readonly object $entity
    ) {
    }
}
