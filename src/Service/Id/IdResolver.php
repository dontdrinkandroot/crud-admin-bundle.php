<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;

/**
 * @extends AbstractProviderService<IdProviderInterface>
 */
class IdResolver extends AbstractProviderService implements IdResolverInterface
{
    #[Override]
    public function resolveId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed
    {
        foreach ($this->providers as $provider) {
            $id = $provider->provideId($entityClass, $crudOperation, $entity);
            if (null !== $id) {
                return $id;
            }
        }

        return null;
    }
}
