<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\HttpFoundation\Request;

/**
 * Base event dispatched before and after an entity is persisted.
 */
abstract class AbstractPersistEvent
{
    public function __construct(
        public readonly string $entityClass,
        public readonly CrudOperation $crudOperation,
        public readonly Request $request,
        public readonly object $data
    ) {
    }
}
