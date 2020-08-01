<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

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
    public function resolve($entityOrClass, string $crudOperation, Request $request): ?string
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof UrlProviderInterface);
            if ($provider->supports(RequestAttributes::getEntityClass($request), $crudOperation, $request)) {
                $title = $provider->provideUrl($entityOrClass, $crudOperation, $request);
                if (null !== $title) {
                    return $title;
                }
            }
        }

        return null;
    }
}
