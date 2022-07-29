<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<TemplateProviderInterface>
 */
class TemplateResolver extends AbstractProviderService implements TemplateResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveTemplate(string $entityClass, CrudOperation $crudOperation): ?string
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->provideTemplate($entityClass, $crudOperation);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
