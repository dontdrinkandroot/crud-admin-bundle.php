<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class ItemResolver extends AbstractProviderService
{
    /**
     * @param class-string  $entityClass
     * @param CrudOperation $crudOperation
     * @param mixed         $id
     *
     * @return object|null
     */
    public function resolve(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof ItemProviderInterface);
            try {
                return $provider->provideItem($entityClass, $crudOperation, $id);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
