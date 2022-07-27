<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class ItemResolver extends AbstractProviderService
{
    /**
     * @param CrudOperation $crudOperation
     * @param class-string  $entityClass
     * @param mixed         $id
     *
     * @return object|null
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass, mixed $id): ?object
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof ItemProviderInterface);
            try {
                return $provider->provideItem($crudOperation, $entityClass, $id);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
