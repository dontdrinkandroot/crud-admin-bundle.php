<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

interface TranslationDomainResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @return ?string
     */
    public function resolveTranslationDomain(string $entityClass): ?string;
}
