<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TemplateProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param string $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportsTemplate(string $crudOperation, string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param string $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return string
     */
    public function provideTemplate(string $crudOperation, string $entityClass): string;
}
