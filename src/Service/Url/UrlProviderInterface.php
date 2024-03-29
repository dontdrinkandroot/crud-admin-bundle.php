<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface UrlProviderInterface extends ProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return string|null
     */
    public function provideUrl(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string;
}
