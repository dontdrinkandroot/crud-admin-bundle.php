<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\CrudOperation;
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
        $key = sprintf("ddr_crud:translation_domain:%s:%s", $entityClass, $crudOperation->value);
        return $this->cache->get($key, fn() => parent::resolve($crudOperation, $entityClass));
    }
}
