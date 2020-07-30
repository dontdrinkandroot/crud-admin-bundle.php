<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TemplateResolver extends AbstractProviderService
{
    /** @var TemplateProviderInterface[] */
    private $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof TemplateProviderInterface);
        $this->providers[] = $provider;
    }

    public function resolve(Request $request): ?string
    {
        if (!$request->attributes->has(RequestAttributes::TEMPLATE)) {
            $request->attributes->set(RequestAttributes::TEMPLATE, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::TEMPLATE);
    }

    public function resolveFromProviders(Request $request)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof TemplateProviderInterface);
            if ($provider->supportsRequest($request)) {
                $result = $provider->provideTemplate($request);
                if (null !== $result) {
                    return $result;
                }
            }
        }

        return null;
    }
}
