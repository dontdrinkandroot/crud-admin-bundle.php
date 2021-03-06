<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TranslationDomainResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): ?string
    {
        if (!$context->isTranslationDomainResolved()) {
            $context->setTranslationDomain($this->resolveFromProviders($context));
            $context->setTranslationDomainResolved();
        }

        return $context->getTranslationDomain();
    }

    public function resolveFromProviders(CrudAdminContext $context): ?string
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof TranslationDomainProviderInterface);
            if ($provider->supportsTranslationDomain($context)) {
                $translationDomain = $provider->resolveTranslationDomain($context);
                if (null !== $translationDomain) {
                    return $translationDomain;
                }
            }
        }

        return null;
    }
}
