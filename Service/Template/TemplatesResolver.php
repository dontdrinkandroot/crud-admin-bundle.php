<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
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

    public function resolve(CrudAdminContext $context): ?array
    {
        if (!$context->isTemplatesResolved()) {
            $templates = [];
            foreach ($this->getProviders() as $provider) {
                assert($provider instanceof TemplatesProviderInterface);
                if ($provider->supports($context)) {
                    $providerTemplates = $provider->provideTemplates($context);
                    if (null !== $providerTemplates) {
                        $templates = array_merge($providerTemplates, $templates);
                    }
                }
            }
            $context->setTemplates($templates);
            $context->setTemplatesResolved();
        }

        return $context->getTemplates();
    }
}
