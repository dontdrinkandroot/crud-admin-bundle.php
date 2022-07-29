<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\ClassNameUtils;

class DefaultTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provideTranslationDomain(string $entityClass): ?string
    {
        return ClassNameUtils::getShortName($entityClass);
    }
}
