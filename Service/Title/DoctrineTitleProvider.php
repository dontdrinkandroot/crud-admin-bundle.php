<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\Inflector\LanguageInflectorFactory;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
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
    public function supports(Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(Request $request): string
    {
        $crudAdminRequest = new CrudAdminRequest($request);
       $shortName = ClassNameUtils::getShortName($crudAdminRequest->getEntityClass());
        switch ($crudAdminRequest->getOperation()) {
            case CrudOperation::LIST:
                return $this->inflector->pluralize($shortName);
            default:
                return $this->inflector->capitalize($shortName);
        }
    }
}
