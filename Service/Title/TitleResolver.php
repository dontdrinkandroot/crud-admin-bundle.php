<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TitleResolver extends AbstractProviderService
{
    public function resolve(Request $request): ?string
    {
        if (!$request->attributes->has(RequestAttributes::TITLE)) {
            $request->attributes->set(RequestAttributes::TITLE, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::TITLE);
    }

    public function resolveFromProviders(Request $request): ?string
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof TitleProviderInterface);
            if ($provider->supports(
                RequestAttributes::getEntityClass($request),
                RequestAttributes::getOperation($request),
                $request
            )) {
                $title = $provider->provideTitle($request);
                if (null !== $title) {
                    return $title;
                }
            }
        }

        return null;
    }
}
