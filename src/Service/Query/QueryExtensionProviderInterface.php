<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Query;

use Doctrine\ORM\Query;

interface QueryExtensionProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     */
    public function extendQuery(string $entityClass, Query $query): void;
}
