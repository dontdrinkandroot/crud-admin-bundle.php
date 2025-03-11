<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

/**
 * @template P
 */
class AbstractProviderService
{
    /** @param iterable<P> $providers */
    public function __construct(protected iterable $providers = [])
    {
    }
}
