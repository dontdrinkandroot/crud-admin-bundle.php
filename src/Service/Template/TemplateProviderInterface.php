<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TemplateProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param CrudOperation $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportsTemplate(CrudOperation $crudOperation, string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return string
     */
    public function provideTemplate(CrudOperation $crudOperation, string $entityClass): string;
}
