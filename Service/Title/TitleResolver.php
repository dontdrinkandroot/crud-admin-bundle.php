<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TitleResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): ?string
    {
        if (!$context->isTitleResolved()) {
            $context->setTitle($this->resolveFromProviders($context));
            $context->setTitleResolved();
        }

        return $context->getTitle();
    }

    public function resolveFromProviders(CrudAdminContext $context): ?string
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof TitleProviderInterface);
            if ($provider->supportsTitle($context)) {
                if (null !== $title = $provider->provideTitle($context)) {
                    return $title;
                }
            }
        }

        return null;
    }
}
