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
    public function resolve(?object $entity = null, string $crudOperation, Request $request): ?string
    {
        $entityClass = RequestAttributes::getEntityClass($request);
        if (null !== $entity) {
            assert(get_class($entity) === RequestAttributes::getEntityClass($request), 'EntityClass not matching');
        }

        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof UrlProviderInterface);
            if ($provider->supports($entityClass, $crudOperation, $request)) {
                $url = $provider->provideUrl($entity, $crudOperation, $request);
                if (null !== $url) {
                    return $url;
                }
            }
        }

        return null;
    }
}
