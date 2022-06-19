<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

/**
 * @template T
 */
class AbstractProviderService
{
    /** @var iterable<T> */
    private iterable $providers;

    /** @param iterable<T> $providers */
    public function __construct(iterable $providers = [])
    {
        $this->providers = $providers;
    }

    /** @return iterable<T> */
    public function getProviders()
    {
        return $this->providers;
    }
}
