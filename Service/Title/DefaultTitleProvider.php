<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Inflector\Inflector;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultTitleProvider implements TitleProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(string $entityClass, string $crudOperation, Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(Request $request): string
    {
        $shortName = ClassNameUtils::getShortName(RequestAttributes::getEntityClass($request));
        switch (RequestAttributes::getOperation($request)) {
            case CrudOperation::LIST:
                return Inflector::pluralize($shortName);
            default:
                return $shortName;
        }
    }
}
