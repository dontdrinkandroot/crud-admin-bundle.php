<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TemplateProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param CrudOperation   $crudOperation
     *
     * @return string
     * @throws UnsupportedByProviderException
     */
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): string;
}
