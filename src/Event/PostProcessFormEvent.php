<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class PostProcessFormEvent
{
    public function __construct(
        public readonly CrudAdminContext $context,
        public readonly FormInterface $form
    ) {
    }
}
