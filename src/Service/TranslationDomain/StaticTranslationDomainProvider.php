<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Override;

/**
 * @template T of object
 * @implements TranslationDomainProviderInterface<T>
 */
class StaticTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     */
    public function __construct(private readonly string $entityClass, private readonly string $translationDomain)
    {
    }

    #[Override]
    public function provideTranslationDomain(string $entityClass): ?string
    {
        if ($entityClass !== $this->entityClass) {
            return null;
        }

        return $this->translationDomain;
    }
}
