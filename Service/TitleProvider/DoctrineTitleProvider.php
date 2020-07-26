<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TitleProvider;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\Inflector\LanguageInflectorFactory;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\Utils\ClassNameUtils;
use ProxyManager\Inflector\ClassNameInflector;

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
    public function supports(CrudAdminRequest $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(CrudAdminRequest $request): string
    {
       $shortName = ClassNameUtils::getShortName($request->getEntityClass());
        switch ($request->getOperation()) {
            case CrudOperation::LIST:
                return $this->inflector->pluralize($shortName);
            default:
                return $this->inflector->capitalize($shortName);
        }
    }
}
