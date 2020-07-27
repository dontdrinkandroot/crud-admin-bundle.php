<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultTitleProvider implements TitleProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsRequest(Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(Request $request): string
    {
        return ClassNameUtils::getShortName(RequestAttributes::getEntityClass($request));
    }
}
