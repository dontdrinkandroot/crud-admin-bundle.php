<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\Common\CrudOperation;

class ProviderCacheKey
{
    public static function create(string $name, CrudOperation $crudOperation, string $entityClass): string
    {
        return sprintf(
            "ddr_crud.%s.%s.%s",
            $name,
            str_replace('\\', '_', $entityClass),
            $crudOperation->value
        );
    }
}
