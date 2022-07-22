<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\ClassNameUtils;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;

class DefaultTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTranslationDomain(string $crudOperation, string $entityClass): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveTranslationDomain(string $crudOperation, string $entityClass): ?string
    {
        return ClassNameUtils::getShortName($entityClass);
    }
}
