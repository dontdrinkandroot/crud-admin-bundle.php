<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;

/**
 * @template P of ItemProviderInterface
 * @extends AbstractProviderService<P>
 */
class ItemResolver extends AbstractProviderService implements ItemResolverInterface
{
    /**
     * @template T of object
     * @return T|null
     */
    #[Override]
    public function resolveItem(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object
    {
        foreach ($this->providers as $provider) {
            /** @var T|null $item */
            $item = $provider->provideItem($entityClass, $crudOperation, $id);
            if (null !== $item) {
                return $item;
            }
        }

        return null;
    }
}
