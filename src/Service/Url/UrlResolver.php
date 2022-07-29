<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class UrlResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation   $crudOperation
     * @param T|null          $entity
     *
     * @return string|null
     */
    public function resolve(string $entityClass, CrudOperation $crudOperation, ?object $entity = null): ?string
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof UrlProviderInterface);
            if ($provider->supportsUrl($entityClass, $crudOperation, $entity)) {
                return $provider->provideUrl($crudOperation, $entityClass, $entity);
            }
        }

        return null;
    }
}
