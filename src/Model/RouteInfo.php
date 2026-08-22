<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

/**
 * Describes the name and path of a CRUD route.
 */
class RouteInfo
{
    public function __construct(public readonly string $name, public readonly string $path)
    {
    }
}
