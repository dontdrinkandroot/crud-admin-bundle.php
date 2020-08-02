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
        $entityClass = $entityOrClass;
        if (is_object($entityOrClass)) {
            $entityClass = get_class($entityClass);
        }

        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof UrlProviderInterface);
            if ($provider->supports($entityClass, $crudOperation, $request)) {
                $url = $provider->provideUrl($entityOrClass, $crudOperation, $request);
                if (null !== $url) {
                    return $url;
                }
            }
        }

        return null;
    }
}
