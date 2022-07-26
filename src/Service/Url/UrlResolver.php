<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class UrlResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return string|null
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass, ?object $entity = null): ?string
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
