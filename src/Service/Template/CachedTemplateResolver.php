<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderCacheKey;
use Symfony\Contracts\Cache\CacheInterface;

class CachedTemplateResolver extends TemplateResolver
{
    public function __construct(iterable $providers, private readonly CacheInterface $cache)
    {
        parent::__construct($providers);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveTemplate(string $entityClass, CrudOperation $crudOperation): ?string
    {
        $key = ProviderCacheKey::create('template', $entityClass, $crudOperation);
        return $this->cache->get($key, fn(): ?string => parent::resolveTemplate($entityClass, $crudOperation));
    }
}
