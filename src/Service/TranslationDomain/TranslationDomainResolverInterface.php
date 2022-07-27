<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\CrudOperation;

interface TranslationDomainResolverInterface
{
    /**
     * @param CrudOperation   $crudOperation
     * @param class-string $entityClass
     *
     * @return ?string
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?string;
}
