<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;

interface TemplateResolverInterface
{
    /**
     * @param class-string  $entityClass
     * @param CrudOperation $crudOperation
     *
     * @return ?string
     */
    public function resolve(string $entityClass, CrudOperation $crudOperation): ?string;
}
