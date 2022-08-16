<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class PreSetDataEvent extends Event
{
    public function __construct(
        public readonly string $entityClass,
        public readonly CrudOperation $crudOperation,
        public readonly Request $request
    ) {
    }
}
