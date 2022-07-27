<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

interface TemplateResolverInterface
{
    /**
     * @param CrudOperation   $crudOperation
     * @param class-string $entityClass
     *
     * @return ?string
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?string;
}
