<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;

/**
 * @extends AbstractProviderService<TemplateProviderInterface>
 */
class TemplateResolver extends AbstractProviderService implements TemplateResolverInterface
{
    #[Override]
    public function resolveTemplate(string $entityClass, CrudOperation $crudOperation): ?string
    {
        foreach ($this->providers as $provider) {
            $template = $provider->provideTemplate($entityClass, $crudOperation);
            if (null !== $template) {
                return $template;
            }
        }

        return null;
    }
}
