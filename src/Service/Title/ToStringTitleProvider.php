<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Override;
use Stringable;

class ToStringTitleProvider implements TitleProviderInterface
{
    /**
     * @psalm-suppress InvalidCast
     */
    #[Override]
    public function provideTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string
    {
        if (
            !in_array($crudOperation, [CrudOperation::READ, CrudOperation::UPDATE], true)
            || null === $entity
            || (!($entity instanceof Stringable) && !method_exists($entity, '__toString'))
        ) {
            return null;
        }

        return (string)$entity;
    }
}
