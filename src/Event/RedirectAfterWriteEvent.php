<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Doctrine\ORM\Mapping\Entity;
use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAfterWriteEvent
{
    /**
     * @param class-string  $entityClass
     * @param CrudOperation $crudOperation
     * @param object        $entity
     * @param Request       $request
     * @param Response|null $response
     */
    public function __construct(
        public readonly string $entityClass,
        public readonly CrudOperation $crudOperation,
        public readonly object $entity,
        public readonly Request $request,
        public ?Response $response = null
    ) {
    }
}
