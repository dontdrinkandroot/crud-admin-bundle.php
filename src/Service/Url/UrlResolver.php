<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<UrlProviderInterface>
 */
class UrlResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param T|null $entity
     *
     */
    public function resolveUrl(string $entityClass, CrudOperation $crudOperation, ?object $entity = null): ?string
    {
        foreach ($this->providers as $provider) {
            $url = $provider->provideUrl($entityClass, $crudOperation, $entity);
            if (null !== $url) {
                return $url;
            }
        }

        return null;
    }
}
