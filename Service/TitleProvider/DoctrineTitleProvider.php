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
        $parts = explode("\\", $request->getEntityClass());
        $lastPart = $parts[count($parts) - 1];
        switch ($request->getOperation()) {
            case CrudOperation::LIST:
                return $this->inflector->pluralize($lastPart);
            default:
                return $this->inflector->capitalize($lastPart);
        }
    }
}
