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
     * @param CrudOperation   $crudOperation
     * @param class-string<T> $entityClass
     * @param T               $entity
     *
     * @return mixed
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass, object $entity): mixed
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof IdProviderInterface);
            try {
                return $provider->provideId($crudOperation, $entityClass, $entity);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
