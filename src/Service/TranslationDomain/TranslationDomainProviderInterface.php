<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface TranslationDomainProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return string|null
     */
    public function provideTranslationDomain(string $entityClass): ?string;
}
