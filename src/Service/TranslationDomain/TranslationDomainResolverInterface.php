<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

/**
 * Resolves the translation domain of an entity class.
 */
interface TranslationDomainResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @return ?string
     */
    public function resolveTranslationDomain(string $entityClass): ?string;
}
