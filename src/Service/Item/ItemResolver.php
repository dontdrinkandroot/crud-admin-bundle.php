<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class ItemResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param mixed           $id
     *
     * @return T|null
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass, mixed $id): ?object
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof ItemProviderInterface);
            if ($provider->supportsItem($crudOperation, $entityClass, $id)) {
                return $provider->provideItem($crudOperation, $entityClass, $id);
            }
        }

        return null;
    }
}
