<?php

namespace Dontdrinkandroot\CrudAdminBundle\Request;

use Symfony\Component\HttpFoundation\Request;

class CrudAdminRequest
{
    private string $crudOperation;

    private Request $request;

    public function __construct(string $crudOperation, Request $request)
    {
        $this->crudOperation = $crudOperation;
        $this->request = $request;
    }

    public function getCrudOperation(): string
    {
        return $this->crudOperation;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getEntityClass(): ?string
    {
        return $this->request->attributes->get('ddr_crud_admin.entity_class');
    }

    public function getId()
    {
        return $this->request->attributes->get('id');
    }

    public function getTemplate(): ?string
    {
        return $this->request->attributes->get('ddr_crud_admin.template');
    }

    public function getData()
    {
        return $this->request->attributes->get('ddr_crud_admin.data');
    }

    public function getRedirectRouteAfterSuccess(): ?string
    {
        return $this->request->attributes->get('ddr_crud_admin.redirect_route_after_success');
    }

    public function getPage(): int
    {
        return $this->getRequest()->query->getInt('page', 1);
    }

    public function getPerPage(): int
    {
        return $this->getRequest()->query->getInt('perPage', 10);
    }
}
