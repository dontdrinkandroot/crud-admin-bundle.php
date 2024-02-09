<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderCacheKey;
use Override;
use Symfony\Contracts\Cache\CacheInterface;

class CachedTranslationDomainResolver extends TranslationDomainResolver
{
    public function __construct(iterable $providers, private readonly CacheInterface $cache)
    {
        parent::__construct($providers);
    }

    #[Override]
    public function resolveTranslationDomain(string $entityClass): ?string
    {
        $key = ProviderCacheKey::create('translation_domain', $entityClass);
        return $this->cache->get($key, fn(): ?string => parent::resolveTranslationDomain($entityClass));
    }
}
