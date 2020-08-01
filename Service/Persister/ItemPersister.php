<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ItemPersister extends AbstractProviderService
{
    public function persistItem(Request $request): bool
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof ItemPersisterProviderInterface);
            if ($provider->supports(
                RequestAttributes::getEntityClass($request),
                RequestAttributes::getOperation($request),
                $request
            )) {
                $result = $provider->persist($request);
                if (true === $result) {
                    RequestAttributes::setPersistSuccess($request, $result);

                    return true;
                }
            }
        }

        return false;
    }
}
