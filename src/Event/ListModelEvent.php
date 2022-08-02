<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

class ListModelEvent
{
    /**
     * @param class-string $entityClass
     * @param array  $context
     */
    public function __construct(public readonly string $entityClass, public array $context)
    {
    }
}
