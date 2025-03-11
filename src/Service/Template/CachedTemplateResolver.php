<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderCacheKey;
use Override;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @template P of TemplateProviderInterface
 * @extends TemplateResolver<P>
 */
class CachedTemplateResolver extends TemplateResolver
{
    /**
     * @param iterable<P> $providers
     */
    public function __construct(iterable $providers, private readonly CacheInterface $cache)
    {
        parent::__construct($providers);
    }

    #[Override]
    public function resolveTemplate(string $entityClass, CrudOperation $crudOperation): ?string
    {
        $key = ProviderCacheKey::create('template', $entityClass, $crudOperation);
        return $this->cache->get($key, fn(): ?string => parent::resolveTemplate($entityClass, $crudOperation));
    }
}
