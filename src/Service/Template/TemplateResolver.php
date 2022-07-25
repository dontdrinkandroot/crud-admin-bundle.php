<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
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
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return ?string
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?string
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof TemplateProviderInterface);
            if ($provider->supportsTemplate($crudOperation, $entityClass)) {
                return $provider->provideTemplate($crudOperation, $entityClass);
            }
        }

        return null;
    }
}
