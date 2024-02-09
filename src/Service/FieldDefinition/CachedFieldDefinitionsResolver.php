<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderCacheKey;
use Override;
use Symfony\Contracts\Cache\CacheInterface;

class CachedFieldDefinitionsResolver extends FieldDefinitionsResolver
{
    public function __construct(iterable $providers, private readonly CacheInterface $cache)
    {
        parent::__construct($providers);
    }

    #[Override]
    public function resolveFieldDefinitions(string $entityClass, CrudOperation $crudOperation): ?array
    {
        $key = ProviderCacheKey::create('field_definitions', $entityClass, $crudOperation);
        return $this->cache->get($key, fn(): ?array => parent::resolveFieldDefinitions($entityClass, $crudOperation));
    }
}
