<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderCacheKey;
use Symfony\Contracts\Cache\CacheInterface;

class CachedTranslationDomainResolver extends TranslationDomainResolver
{
    public function __construct(iterable $providers, private readonly CacheInterface $cache)
    {
        parent::__construct($providers);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?string
    {
        $key = ProviderCacheKey::create('translation_domain', $crudOperation, $entityClass);
        return $this->cache->get($key, fn() => parent::resolve($crudOperation, $entityClass));
    }
}
