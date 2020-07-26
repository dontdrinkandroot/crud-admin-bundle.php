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
    const ATTRIBUTE_OPERATION = 'ddr_crud_admin.operation';
    const ATTRIBUTE_ENTITY_CLASS = 'ddr_crud_admin.entity_class';
    const ATTRIBUTE_DATA = 'ddr_crud_admin.data';
    const ATTRIBUTE_TITLE = 'ddr_crud_admin.title';
    const ATTRIBUTE_FIELD_DEFINITIONS = 'ddr_crud_admin.field_definitions';
    const ATTRIBUTE_ROUTES = 'ddr_crud_admin.routes';
    const ATTRIBUTE_FORM = 'ddr_crud_admin.form';

    private Request $request;

    public function __construct(string $crudOperation, Request $request)
    {
        $this->request = $request;
        $this->setOperation($crudOperation);
    }

    public function getOperation(): string
    {
        return $this->request->attributes->get(self::ATTRIBUTE_OPERATION);
    }

    public function setOperation(string $crudOperation)
    {
        $this->request->attributes->set(self::ATTRIBUTE_OPERATION, $crudOperation);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getEntityClass(): ?string
    {
        return $this->request->attributes->get(self::ATTRIBUTE_ENTITY_CLASS);
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
        return $this->request->attributes->get(self::ATTRIBUTE_DATA);
    }

    public function setData($data)
    {
        $this->request->attributes->set(self::ATTRIBUTE_DATA, $data);
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
        return $this->request->attributes->get(self::ATTRIBUTE_TITLE);
    }

    public function setTitle(string $title): void
    {
        $this->request->attributes->set(self::ATTRIBUTE_TITLE, $title);
    }

    /**
     * @return FieldDefinition[]|null
     */
    public function getFieldDefinitions(): ?array
    {
        return $this->request->attributes->get(self::ATTRIBUTE_FIELD_DEFINITIONS);
    }

    /**
     * @param FieldDefinition[] $fieldDefinitions
     */
    public function setFieldDefinitions(array $fieldDefinitions)
    {
        $this->request->attributes->set(self::ATTRIBUTE_FIELD_DEFINITIONS, $fieldDefinitions);
    }

    public function getRoutes(): ?array
    {
        return $this->request->attributes->get(self::ATTRIBUTE_ROUTES);
    }

    public function setRoutes(array $routes): void
    {
        $this->request->attributes->set(self::ATTRIBUTE_ROUTES, $routes);
    }

    public function getForm(): ?FormInterface
    {
        return $this->request->attributes->get(self::ATTRIBUTE_FORM);
    }

    public function setForm(FormInterface $form): void
    {
        $this->request->attributes->set(self::ATTRIBUTE_FORM, $form);
    }

}
