<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface TemplateProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return string|null
     */
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): ?string;
}
