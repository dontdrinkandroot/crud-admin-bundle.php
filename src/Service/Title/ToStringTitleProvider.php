<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;

class ToStringTitleProvider implements TitleProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTitle(CrudAdminContext $context): bool
    {
        return in_array($context->getCrudOperation(), [CrudOperation::READ, CrudOperation::UPDATE], true)
            && (null !== $entity = $context->getEntity())
            && method_exists($entity, '__toString');
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(CrudAdminContext $context): ?string
    {
        return (string)$context->getEntity();
    }
}
