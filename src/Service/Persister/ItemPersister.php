<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class ItemPersister extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T $entity
     */
    public function persistItem(string $crudOperation, string $entityClass, object $entity): void
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof ItemPersisterProviderInterface);
            if ($provider->supportsPersist($crudOperation, $entityClass, $entity)) {
                $provider->persist($crudOperation, $entityClass, $entity);
                return;
            }
        }
    }
}
