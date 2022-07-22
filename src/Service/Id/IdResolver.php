<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class IdResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T               $entity
     *
     * @return mixed
     */
    public function resolve(string $crudOperation, string $entityClass, object $entity): mixed
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof IdProviderInterface);
            if ($provider->supportsId($crudOperation, $entityClass, $entity)) {
                return $provider->provideId($crudOperation, $entityClass, $entity);
            }
        }

        return null;
    }
}
