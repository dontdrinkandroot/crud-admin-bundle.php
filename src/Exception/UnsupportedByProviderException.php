<?php

namespace Dontdrinkandroot\CrudAdminBundle\Exception;

use Dontdrinkandroot\Common\CrudOperation;
use Exception;

class UnsupportedByProviderException extends Exception
{
    /**
     * @param class-string       $entityClass
     * @param CrudOperation|null $crudOperation
     * @param object|null        $entity
     */
    public function __construct(
        public readonly string $entityClass,
        public readonly ?CrudOperation $crudOperation = null,
        public ?object $entity = null
    ) {
        parent::__construct(sprintf('%s::%s', $this->entityClass, $this->crudOperation?->value ?? 'none'));
    }
}
