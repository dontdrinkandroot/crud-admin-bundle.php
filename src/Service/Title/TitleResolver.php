<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class TitleResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null $entity
     *
     * @return ?string
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass, ?object $entity): ?string
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof TitleProviderInterface);
            if ($provider->supportsTitle($crudOperation, $entityClass, $entity)) {
                return $provider->provideTitle($crudOperation, $entityClass, $entity);
            }
        }

        return null;
    }
}
