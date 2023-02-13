<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

class StaticTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * @param class-string $entityClass
     */
    public function __construct(private readonly string $entityClass, private readonly string $translationDomain)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function provideTranslationDomain(string $entityClass): ?string
    {
        if ($entityClass !== $this->entityClass) {
            return null;
        }

        return $this->translationDomain;
    }
}
