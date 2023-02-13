<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<ItemPersisterProviderInterface>
 */
class ItemPersister extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param T $entity
     */
    public function persistItem(CrudOperation $crudOperation, string $entityClass, object $entity): void
    {
        foreach ($this->providers as $provider) {
            $result = $provider->persist($entityClass, $crudOperation, $entity);
            if (true === $result) {
                return;
            }
        }
    }
}
