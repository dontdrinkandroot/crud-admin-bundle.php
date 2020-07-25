<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TitleProvider;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\Utils\ClassNameUtils;

class DefaultTitleProvider implements TitleProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminRequest $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(CrudAdminRequest $request): ?string
    {
        return ClassNameUtils::getShortName($request->getEntityClass());
    }
}
