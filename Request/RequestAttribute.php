<?php

namespace Dontdrinkandroot\CrudAdminBundle\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class RequestAttribute
{
    public const DATA = 'ddr_crud_admin.data';
    public const ROUTES = 'ddr_crud_admin.routes';
    public const FIELD_DEFINITIONS = 'ddr_crud_admin.field_definitions';
    public const ENTITY_CLASS = 'ddr_crud_admin.entity_class';
    public const TITLE = 'ddr_crud_admin.title';
    public const OPERATION = 'ddr_crud_admin.operation';
    public const FORM = 'ddr_crud_admin.form';
    public const TEMPLATE = 'ddr_crud_admin.template';
}
