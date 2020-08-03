<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Service\ServiceProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TranslationDomainResolver extends AbstractProviderService
{
    public function resolve(string $entityClass, string $crudOperation, Request $request): ?string
    {
        if (!$request->attributes->has(RequestAttributes::TRANSLATION_DOMAIN)) {
            $request->attributes->set(
                RequestAttributes::TRANSLATION_DOMAIN,
                $this->resolveFromProviders($entityClass, $crudOperation, $request)
            );
        }

        return $request->attributes->get(RequestAttributes::TRANSLATION_DOMAIN);
    }

    public function resolveFromProviders(string $entityClass, string $crudOperation, Request $request): ?string
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof TranslationDomainProviderInterface);
            if ($provider->supports($entityClass, $crudOperation, $request)) {
                $translationDomain = $provider->resolveTranslationDomain($entityClass, $crudOperation, $request);
                if (null !== $translationDomain) {
                    return $translationDomain;
                }
            }
        }

        return null;
    }
}
