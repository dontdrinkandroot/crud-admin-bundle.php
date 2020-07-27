<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\Utils\ClassNameUtils;
use ProxyManager\Inflector\ClassNameInflector;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineTitleProvider implements TitleProviderInterface
{
    private Inflector $inflector;

    public function __construct()
    {
        $this->inflector = InflectorFactory::create()->build();
    }

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
        $shortName = ClassNameUtils::getShortName(RequestAttributes::getEntityClass($request));
        switch (RequestAttributes::getOperation($request)) {
            case CrudOperation::LIST:
                return $this->inflector->pluralize($shortName);
            default:
                return $this->inflector->capitalize($shortName);
        }
    }
}
