<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use RuntimeException;
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
        assert($provider instanceof ProviderInterface);
        $this->providers[] = $provider;
    }

    public function resolve(Request $request): string
    {
        $crudAdminRequest = new CrudAdminRequest($request);
        $title = $crudAdminRequest->getTitle();
        if (null !== $title) {
            return $title;
        }

        foreach ($this->providers as $titleProvider) {
            if ($titleProvider->supports($request)) {
                $title = $titleProvider->provideTitle($request);
                if (null !== $title) {
                    $crudAdminRequest->setTitle($title);

                    return $title;
                }
            }
        }

        throw new RuntimeException('Could not resolve title');
    }
}
