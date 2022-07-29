<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderCacheKey;
use Symfony\Contracts\Cache\CacheInterface;

class CachedRouteInfoResolver extends RouteInfoResolver
{
    public function __construct(iterable $providers, private readonly CacheInterface $cache)
    {
        parent::__construct($providers);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?RouteInfo
    {
        $key = ProviderCacheKey::create('route_info', $entityClass, $crudOperation);
        return $this->cache->get($key, fn() => parent::resolve($crudOperation, $entityClass));
    }
}
