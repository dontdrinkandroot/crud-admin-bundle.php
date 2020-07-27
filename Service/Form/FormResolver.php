<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttribute;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class FormResolver implements ProviderServiceInterface
{
    /** @var FormProviderInterface[] */
    private $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof FormProviderInterface);
        $this->providers[] = $provider;
    }

    public function resolve(Request $request) {
        if (!$request->attributes->has(RequestAttribute::FORM)) {
            $request->attributes->set(RequestAttribute::FORM, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttribute::FORM);
    }

    public function resolveFromProviders(Request $request)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($request)) {
                $result = $provider->provideForm($request);
                if (null !== $result) {
                    return $result;
                }
            }
        }

        return null;
    }
}
