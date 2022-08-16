<?php

namespace Dontdrinkandroot\CrudAdminBundle\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AbortWithResponseException extends Exception
{
    public function __construct(public readonly Response $response)
    {
        parent::__construct('Aborting with response', $this->response->getStatusCode());
    }
}
