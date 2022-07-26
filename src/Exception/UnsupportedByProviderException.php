<?php

namespace Dontdrinkandroot\CrudAdminBundle\Exception;

use Dontdrinkandroot\Common\CrudOperation;
use Exception;

class UnsupportedByProviderException extends Exception
{
    /**
     * @param CrudOperation $crudOperation
     * @param class-string  $entityClass
     * @param object|null   $entity
     */
    public function __construct(
        public readonly CrudOperation $crudOperation,
        public readonly string $entityClass,
        public ?object $entity = null
    ) {
        parent::__construct(sprintf('%s::%s', $this->crudOperation->value, $this->entityClass));
    }
}
