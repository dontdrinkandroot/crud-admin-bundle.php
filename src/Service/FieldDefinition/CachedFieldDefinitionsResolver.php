<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Contracts\Cache\CacheInterface;

class CachedFieldDefinitionsResolver extends FieldDefinitionsResolver
{
    public function __construct(iterable $providers, private readonly CacheInterface $cache)
    {
        parent::__construct($providers);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?array
    {
        $key = sprintf("ddr_crud:field_definitions:%s:%s", $entityClass, $crudOperation->value);
        return $this->cache->get($key, fn() => parent::resolve($crudOperation, $entityClass));
    }
}
