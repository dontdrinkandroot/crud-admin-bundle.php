<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\CrudAdminBundle\Exception\EndProviderChainException;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class UrlResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): ?string
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof UrlProviderInterface);
            if ($provider->supports($context)) {
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
