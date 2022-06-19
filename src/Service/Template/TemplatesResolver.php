<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<TemplatesProviderInterface>
 */
class TemplatesResolver extends AbstractProviderService
{
    public function resolve(CrudAdminContext $context): ?array
    {
        if (!$context->isTemplatesResolved()) {
            $templates = [];
            foreach ($this->getProviders() as $provider) {
                assert($provider instanceof TemplatesProviderInterface);
                if ($provider->supportsTemplates($context)) {
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
