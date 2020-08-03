<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CreateResponseEvent
{
    private Response $response;

    private CrudAdminContext $context;

    public function __construct(CrudAdminContext $context, Response $response)
    {
        $this->response = $response;
        $this->context = $context;
    }

    public function getRequest(): Request
    {
        return $this->context->getRequest();
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getContext(): CrudAdminContext
    {
        return $this->context;
    }
}
