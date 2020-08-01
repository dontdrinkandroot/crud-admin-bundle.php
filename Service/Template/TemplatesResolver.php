<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TemplatesResolver extends AbstractProviderService
{
    /** @var TemplatesProviderInterface[] */
    private $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof TemplatesProviderInterface);
        $this->providers[] = $provider;
    }

    public function resolve(Request $request): ?array
    {
        if (!$request->attributes->has(RequestAttributes::TEMPLATES)) {
            $request->attributes->set(RequestAttributes::TEMPLATES, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::TEMPLATES);
    }

    public function resolveFromProviders(Request $request)
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof TemplatesProviderInterface);
            if ($provider->supports(
                RequestAttributes::getEntityClass($request),
                RequestAttributes::getOperation($request),
                $request
            )) {
                $result = $provider->provideTemplates($request);
                if (null !== $result) {
                    return $result;
                }
            }
        }

        return null;
    }
}
