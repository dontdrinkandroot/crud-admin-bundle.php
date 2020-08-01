<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class FormResolver extends AbstractProviderService
{
    public function resolve(Request $request): ?FormInterface
    {
        if (!$request->attributes->has(RequestAttributes::FORM)) {
            $request->attributes->set(RequestAttributes::FORM, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::FORM);
    }

    public function resolveFromProviders(Request $request)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof FormProviderInterface);
            if ($provider->supports(
                RequestAttributes::getEntityClass($request),
                RequestAttributes::getOperation($request),
                $request
            )) {
                $result = $provider->provideForm($request);
                if (null !== $result) {
                    return $result;
                }
            }
        }

        return null;
    }
}
