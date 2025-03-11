<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;

/**
 * @extends AbstractProviderService<TitleProviderInterface>
 */
class TitleResolver extends AbstractProviderService implements TitleResolverInterface
{
    #[Override]
    public function resolveTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string
    {
        foreach ($this->providers as $provider) {
            $title = $provider->provideTitle($entityClass, $crudOperation, $entity);
            if (null !== $title) {
                return $title;
            }
        }

        return null;
    }
}
