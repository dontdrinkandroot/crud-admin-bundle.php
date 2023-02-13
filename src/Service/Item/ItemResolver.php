<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\EntityNotFoundException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<ItemProviderInterface>
 */
class ItemResolver extends AbstractProviderService
{
    /**
     * @param class-string $entityClass
     * @throws EntityNotFoundException
     */
    public function resolveItem(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object
    {
        foreach ($this->providers as $provider) {
            $item = $provider->provideItem($entityClass, $crudOperation, $id);
            if (null !== $item) {
                return $item;
            }
        }

        return null;
    }
}
