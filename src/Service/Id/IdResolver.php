<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class IdResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation   $crudOperation
     * @param T               $entity
     *
     * @return mixed
     */
    public function resolve(string $entityClass, CrudOperation $crudOperation, object $entity): mixed
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof IdProviderInterface);
            try {
                return $provider->provideId($entityClass, $crudOperation, $entity);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
