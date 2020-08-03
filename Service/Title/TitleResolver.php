<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\HttpFoundation\Request;

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
            if ($provider->supports($context)) {
                $title = $provider->provideTitle($context);
                if (null !== $title) {
                    return $title;
                }
            }
        }

        return null;
    }
}
