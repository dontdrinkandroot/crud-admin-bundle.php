<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

class RouteInfo
{
    public function __construct(public readonly string $name, public readonly string $path)
    {
    }
}
