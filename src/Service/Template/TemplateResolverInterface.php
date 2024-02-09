<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;

interface TemplateResolverInterface
{
    /**
     * @param class-string  $entityClass
     *
     * @return ?string
     */
    public function resolveTemplate(string $entityClass, CrudOperation $crudOperation): ?string;
}
