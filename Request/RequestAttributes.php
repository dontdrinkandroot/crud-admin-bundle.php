<?php

namespace Dontdrinkandroot\CrudAdminBundle\Request;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Symfony\Component\HttpFoundation\Request;

class RequestAttributes
{
    public const ROUTES_PREFIX = 'ddr_crud_admin.routes.prefix';
    public const FIELDS = 'ddr_crud_admin.fields';
    public const ENTITY_CLASS = 'ddr_crud_admin.entity_class';
    public const DEFAULT_SORT_FIELD_NAME = 'ddr_crud_admin.default_sort_field_name';
    public const DEFAULT_SORT_DIRECTION = 'ddr_crud_admin.default_sort_direction';
    public const FORM_TYPE = 'ddr_crud_admin.form_type';
    public const TEMPLATES_PATH = 'ddr_crud_admin.templates.path';
    public const TRANSLATION_DOMAIN = 'ddr_crud_admin.translation_domain';
    public const TEMPLATES = 'ddr_crud_admin.templates';

    public static function getId(Request $request)
    {
        return $request->attributes->get('id');
    }

    public static function getEntityClass(Request $request): ?string
    {
        return $request->attributes->get(self::ENTITY_CLASS);
    }

    public static function getDefaultSortFieldName(Request $request)
    {
        return $request->attributes->get(self::DEFAULT_SORT_FIELD_NAME);
    }

    public static function getDefaultSortDirection(Request $request)
    {
        return $request->attributes->get(self::DEFAULT_SORT_DIRECTION);
    }

    public static function getFormType(Request $request): ?string
    {
        return $request->attributes->get(self::FORM_TYPE);
    }

    public static function getRoutesPrefix(Request $request): ?string
    {
        return $request->attributes->get(self::ROUTES_PREFIX);
    }

    public static function getTemplatesPath(Request $request)
    {
        return $request->attributes->get(self::TEMPLATES_PATH);
    }

    public static function getFields(Request $request)
    {
        return $request->attributes->get(self::FIELDS);
    }

    public static function entityClassMatches(CrudAdminContext $context)
    {
        return self::getEntityClass($context->getRequest()) === $context->getEntityClass();
    }

    public static function getTemplates(Request $request)
    {
        return $request->attributes->get(self::TEMPLATES);
    }

    public static function getTranslationDomain(Request $request): ?string
    {
        return $request->attributes->get(self::TRANSLATION_DOMAIN);
    }
}
