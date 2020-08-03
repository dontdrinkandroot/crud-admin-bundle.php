<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

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
                $url = $provider->provideUrl($context);
                if (null !== $url) {
                    return $url;
                }
            }
        }

        return null;
    }
}
