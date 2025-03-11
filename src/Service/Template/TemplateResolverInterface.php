<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;

interface TemplateResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @return ?string
     */
    public function resolveTemplate(string $entityClass, CrudOperation $crudOperation): ?string;
}
