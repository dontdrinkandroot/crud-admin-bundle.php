<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<TemplateProviderInterface>
 */
class TemplateResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return ?string
     */
    public function resolve(string $crudOperation, string $entityClass): ?string
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof TemplateProviderInterface);
            if ($provider->supportsTemplate($crudOperation, $entityClass)) {
                return $provider->provideTemplate($crudOperation, $entityClass);
            }
        }

        return null;
    }
}
