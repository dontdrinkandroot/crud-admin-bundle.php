<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TitleResolver implements ProviderServiceInterface
{
    /** @var TitleProviderInterface[] */
    private array $providers = [];

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
        if (!$request->attributes->has(RequestAttributes::TITLE)) {
            $request->attributes->set(RequestAttributes::TITLE, $this->resolveFromProviders($request));
        }

        return $request->attributes->get(RequestAttributes::TITLE);
    }

    public function resolveFromProviders(Request $request): ?string
    {
        foreach ($this->providers as $titleProvider) {
            if ($titleProvider->supports($request)) {
                $title = $titleProvider->provideTitle($request);
                if (null !== $title) {
                    return $title;
                }
            }
        }

        return null;
    }
}
