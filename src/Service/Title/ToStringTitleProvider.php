<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Stringable;

class ToStringTitleProvider implements TitleProviderInterface
{
    /**
     * {@inheritdoc}
     * @psalm-suppress InvalidCast
     */
    public function provideTitle(CrudOperation $crudOperation, string $entityClass, ?object $entity): string
    {
        if (
            !in_array($crudOperation, [CrudOperation::READ, CrudOperation::UPDATE], true)
            || null === $entity
            || (!($entity instanceof Stringable) && !method_exists($entity, '__toString'))
        ) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass, $entity);
        }

        return (string)$entity;
    }
}
