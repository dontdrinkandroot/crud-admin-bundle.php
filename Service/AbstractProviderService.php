<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class AbstractProviderService
{
    private iterable $providers;

    public function __construct(iterable $providers = [])
    {
        $this->providers = $providers;
    }

    /**
     * @return array|iterable
     */
    public function getProviders()
    {
        return $this->providers;
    }
}
