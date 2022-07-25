<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\ClassNameUtils;
use Dontdrinkandroot\Common\CrudOperation;

class DefaultTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTranslationDomain(CrudOperation $crudOperation, string $entityClass): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveTranslationDomain(CrudOperation $crudOperation, string $entityClass): ?string
    {
        return ClassNameUtils::getShortName($entityClass);
    }
}
