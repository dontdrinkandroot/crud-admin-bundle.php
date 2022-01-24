<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Symfony\Component\HttpFoundation\Response;

class CreateResponseEvent
{
    public function __construct(
        public readonly CrudAdminContext $context,
        public readonly Response $response
    ) {
    }
}
