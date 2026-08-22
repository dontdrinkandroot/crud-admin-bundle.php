<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\Common\CrudOperation;

/**
 * Creates cache keys for cached resolvers.
 */
class ProviderCacheKey
{
    /**
     * @param class-string $entityClass
     */
    public static function create(string $name, string $entityClass, ?CrudOperation $crudOperation = null): string
    {
        if (null !== $crudOperation) {
            return sprintf(
                "ddr_crud.%s.%s.%s",
                $name,
                str_replace('\\', '_', $entityClass),
                $crudOperation->value
            );
        }

        return sprintf(
            "ddr_crud.%s.%s",
            $name,
            str_replace('\\', '_', $entityClass)
        );
    }
}
