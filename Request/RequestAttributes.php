<?php

namespace Dontdrinkandroot\CrudAdminBundle\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class RequestAttributes
{
    public const DATA = 'ddr_crud_admin.data';
    public const ROUTES = 'ddr_crud_admin.routes';
    public const FIELD_DEFINITIONS = 'ddr_crud_admin.field_definitions';
    public const ENTITY_CLASS = 'ddr_crud_admin.entity_class';
    public const TITLE = 'ddr_crud_admin.title';
    public const OPERATION = 'ddr_crud_admin.operation';
    public const FORM = 'ddr_crud_admin.form';
    public const TEMPLATE = 'ddr_crud_admin.template';
    const PERSIST_SUCCESS = 'ddr_crud_admin.persist_success';

    public static function getEntityClass(Request $request): ?string
    {
        return $request->attributes->get(self::ENTITY_CLASS);
    }

    public static function getOperation(Request $request): ?string
    {
        return $request->attributes->get(self::OPERATION);
    }

    public static function getData(Request $request): ?object
    {
        return $request->attributes->get(self::DATA);
    }

    public static function setData(Request $request, object $data): void
    {
        $request->attributes->set(self::DATA, $data);
    }

    public static function setPersistSuccess(Request $request, bool $result)
    {
        $request->attributes->set(self::PERSIST_SUCCESS, $result);
    }

    public static function getPersistSuccess(Request $request): ?bool
    {
        return $request->attributes->get(self::PERSIST_SUCCESS);
    }
}
