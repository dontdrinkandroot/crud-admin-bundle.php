<?php

namespace Dontdrinkandroot\CrudAdminBundle\Exception;

use Dontdrinkandroot\Common\CrudOperation;
use Exception;

class EntityNotFoundException extends Exception
{
    public function __construct(
        public readonly string $entityClass,
        public readonly CrudOperation $crudOperation,
        public readonly mixed $id
    ) {
        parent::__construct(sprintf('Entity not found %s::%s[%s]', $entityClass, $this->crudOperation->value, $id));
    }
}
