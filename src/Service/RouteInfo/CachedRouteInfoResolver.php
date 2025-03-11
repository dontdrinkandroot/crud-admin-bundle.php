<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderCacheKey;
use Override;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @template P of RouteInfoProviderInterface
 * @extends RouteInfoResolver<P>
 */
class CachedRouteInfoResolver extends RouteInfoResolver
{
    /**
     * @param iterable<P> $providers
     */
    public function __construct(iterable $providers, private readonly CacheInterface $cache)
    {
        parent::__construct($providers);
    }

    #[Override]
    public function resolveRouteInfo(string $entityClass, CrudOperation $crudOperation): ?RouteInfo
    {
        $key = ProviderCacheKey::create('route_info', $entityClass, $crudOperation);
        return $this->cache->get($key, fn(): ?RouteInfo => parent::resolveRouteInfo($entityClass, $crudOperation));
    }
}
