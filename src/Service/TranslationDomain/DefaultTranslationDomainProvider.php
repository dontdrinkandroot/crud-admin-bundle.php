<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\ClassNameUtils;
use Override;

/**
 * @template T of object
 * @implements TranslationDomainProviderInterface<T>
 */
class DefaultTranslationDomainProvider implements TranslationDomainProviderInterface
{
    #[Override]
    public function provideTranslationDomain(string $entityClass): ?string
    {
        return ClassNameUtils::getShortName($entityClass);
    }
}
