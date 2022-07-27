<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
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
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?string
    {
        $key = sprintf("ddr_crud:template:%s:%s", $entityClass, $crudOperation->value);
        return $this->cache->get($key, fn() => parent::resolve($crudOperation, $entityClass));
    }
}
