<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Stringable;

class ToStringTitleProvider implements TitleProviderInterface
{
    /**
     * {@inheritdoc}
     * @psalm-suppress InvalidCast
     */
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
