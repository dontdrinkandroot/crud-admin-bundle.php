<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;

/**
 * @extends AbstractProviderService<ItemPersisterProviderInterface>
 */
class ItemPersister extends AbstractProviderService implements ItemPersisterInterface
{
    #[Override]
    public function persistItem(CrudOperation $crudOperation, string $entityClass, object $entity): void
    {
        foreach ($this->providers as $provider) {
            $result = $provider->persist($entityClass, $crudOperation, $entity);
            if (true === $result) {
                return;
            }
        }
    }
}
