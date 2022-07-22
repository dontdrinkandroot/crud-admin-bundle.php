<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Stringable;

class ToStringTitleProvider implements TitleProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTitle(string $crudOperation, string $entityClass, ?object $entity): bool
    {
        return in_array($crudOperation, [CrudOperation::READ, CrudOperation::UPDATE], true)
            && null !== $entity
            && ($entity instanceof Stringable || method_exists($entity, '__toString'));
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(string $crudOperation, string $entityClass, ?object $entity): ?string
    {
        return (string)$entity;
    }
}
