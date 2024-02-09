<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\ClassNameUtils;
use Override;

class DefaultTranslationDomainProvider implements TranslationDomainProviderInterface
{
    #[Override]
    public function provideTranslationDomain(string $entityClass): ?string
    {
        return ClassNameUtils::getShortName($entityClass);
    }
}
