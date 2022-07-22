<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\CrudAdminBundle\Exception\EndProviderChainException;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class UrlResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return string|null
     */
    public function resolve(string $crudOperation, string $entityClass, ?object $entity): ?string
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof UrlProviderInterface);
            if ($provider->supportsUrl($crudOperation, $entityClass, $entity)) {
                return $provider->provideUrl($crudOperation, $entityClass, $entity);
            }
        }

        return null;
    }
}
