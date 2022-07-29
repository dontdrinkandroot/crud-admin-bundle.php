<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

interface TranslationDomainResolverInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return ?string
     */
    public function resolveTranslationDomain(string $entityClass): ?string;
}
