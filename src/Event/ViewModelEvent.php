<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\HttpFoundation\Request;

class ViewModelEvent
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param array<string, mixed> $context
     */
    public function __construct(
        public readonly string $entityClass,
        public readonly CrudOperation $crudOperation,
        public array $context,
        public Request $request
    ) {
    }
}
