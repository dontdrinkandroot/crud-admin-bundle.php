<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<IdProviderInterface>
 */
class IdResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param T $entity
     *
     */
    public function resolveId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed
    {
        foreach ($this->providers as $provider) {
            $id = $provider->provideId($entityClass, $crudOperation, $entity);
            if (null !== $id) {
                return $id;
            }
        }

        return null;
    }
}
