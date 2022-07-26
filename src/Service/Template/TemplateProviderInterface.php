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
     * @param CrudOperation $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return string
     * @throws UnsupportedByProviderException
     */
    public function provideTemplate(CrudOperation $crudOperation, string $entityClass): string;
}
