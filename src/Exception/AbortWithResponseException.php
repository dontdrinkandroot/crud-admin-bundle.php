<?php

namespace Dontdrinkandroot\CrudAdminBundle\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Thrown by event listeners to abort the current request and return the given response.
 */
class AbortWithResponseException extends Exception
{
    public function __construct(public readonly Response $response)
    {
        parent::__construct('Aborting with response', $this->response->getStatusCode());
    }
}
