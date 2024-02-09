<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TemplateProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return string|null
     */
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): ?string;
}
