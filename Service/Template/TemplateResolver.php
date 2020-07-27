<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttribute;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TemplateResolver implements ProviderServiceInterface
{
    /** @var TemplateProviderInterface[] */
    private $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(ProviderInterface $provider): void
    {
        assert($provider instanceof TitleProviderInterface);
        $this->providers[] = $provider;
    }

    public function resolve(Request $request): ?string
    {
        if (!$request->attributes->has(RequestAttribute::TEMPLATE)) {
            $request->attributes->set(RequestAttribute::TEMPLATE, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttribute::TEMPLATE);
    }

    public function resolveFromProviders(Request $request)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($request)) {
                $result = $provider->provide($request);
                if (null !== $result) {
                    return $result;
                }
            }
        }

        return null;
    }
}