<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\HttpFoundation\Request;

class ViewModelEvent
{
    /**
     * @param class-string $entityClass
     */
    public function __construct(
        public readonly string $entityClass,
        public readonly CrudOperation $crudOperation,
        public array $context,
        public Request $request
    ) {
    }
}
