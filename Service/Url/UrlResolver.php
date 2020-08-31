<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\CrudAdminBundle\Exception\EndProviderChainException;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class UrlResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): ?string
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof UrlProviderInterface);
            if ($provider->supportsUrl($context)) {
                try {
                    $url = $provider->provideUrl($context);
                } catch (EndProviderChainException $e) {
                    return null;
                }

                if (null !== $url) {
                    return $url;
                }
            }
        }

        return null;
    }
}
