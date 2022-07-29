<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<TitleProviderInterface>
 */
class TitleResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation   $crudOperation
     * @param T|null          $entity
     *
     * @return ?string
     */
    public function resolveTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->provideTitle($entityClass, $crudOperation, $entity);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
