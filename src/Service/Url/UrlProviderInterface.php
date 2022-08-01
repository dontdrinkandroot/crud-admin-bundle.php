<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface UrlProviderInterface extends ProviderInterface
{
    /**
     * @param class-string  $entityClass
     * @param CrudOperation $crudOperation
     * @param ?object       $entity
     *
     * @return string
     * @throws UnsupportedByProviderException
     */
    public function provideUrl(string $entityClass, CrudOperation $crudOperation, ?object $entity): string;
}
