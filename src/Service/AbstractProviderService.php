<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

/**
 * @template T
 */
class AbstractProviderService
{
    /** @param iterable<T> $providers */
    public function __construct(protected iterable $providers = [])
    {
    }
}
