<?php

namespace Dontdrinkandroot\CrudAdminBundle\Request;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CrudAdminRequest
{
    private Request $request;

    public function __construct(Request $request, string $crudOperation = null)
    {
        $this->request = $request;
        if (null !== $crudOperation) {
            $this->setOperation($crudOperation);
        }
    }

    public function getOperation(): string
    {
        return $this->request->attributes->get(RequestAttributes::OPERATION);
    }

    public function setOperation(string $crudOperation)
    {
        $this->request->attributes->set(RequestAttributes::OPERATION, $crudOperation);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getEntityClass(): ?string
    {
        return $this->request->attributes->get(RequestAttributes::ENTITY_CLASS);
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
        return $this->request->attributes->get(RequestAttributes::DATA);
    }

    public function setData($data)
    {
        $this->request->attributes->set(RequestAttributes::DATA, $data);
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

    public function getTitle(): ?string
    {
        return $this->request->attributes->get(RequestAttributes::TITLE);
    }

    public function setTitle(string $title): void
    {
        $this->request->attributes->set(RequestAttributes::TITLE, $title);
    }

    /**
     * @return FieldDefinition[]|null
     */
    public function getFieldDefinitions(): ?array
    {
        return $this->request->attributes->get(RequestAttributes::FIELD_DEFINITIONS);
    }

    /**
     * @param FieldDefinition[] $fieldDefinitions
     */
    public function setFieldDefinitions(array $fieldDefinitions)
    {
        $this->request->attributes->set(RequestAttributes::FIELD_DEFINITIONS, $fieldDefinitions);
    }

    public function getRoutes(): ?array
    {
        return $this->request->attributes->get(RequestAttributes::ROUTES);
    }

    public function setRoutes(array $routes): void
    {
        $this->request->attributes->set(RequestAttributes::ROUTES, $routes);
    }

    public function getForm(): ?FormInterface
    {
        return $this->request->attributes->get(RequestAttributes::FORM);
    }

    public function setForm(FormInterface $form): void
    {
        $this->request->attributes->set(RequestAttributes::FORM, $form);
    }

}
