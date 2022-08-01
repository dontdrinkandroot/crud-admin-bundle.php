<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;

class StaticTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * @param class-string $entityClass
     * @param string       $translationDomain
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
            throw new UnsupportedByProviderException($entityClass);
        }

        return $this->translationDomain;
    }
}
