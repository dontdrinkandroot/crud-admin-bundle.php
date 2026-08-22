<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

/**
 * Base service that iterates over providers.
 *
 * @template P
 */
class AbstractProviderService
{
    /** @param iterable<P> $providers */
    public function __construct(protected iterable $providers = [])
    {
    }
}
