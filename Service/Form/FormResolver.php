<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\Form\FormInterface;

/**
 * @extends AbstractProviderService<FormProviderInterface>
 */
class FormResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): ?FormInterface
    {
        if (!$context->isFormResolved()) {
            $context->setForm($this->resolveFromProviders($context));
            $context->setFormResolved();
        }

       return $context->getForm();
    }

    public function resolveFromProviders(CrudAdminContext $context)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof FormProviderInterface);
            if ($provider->supportsForm($context)) {
                $result = $provider->provideForm($context);
                if (null !== $result) {
                    return $result;
                }
            }
        }

        return null;
    }
}
