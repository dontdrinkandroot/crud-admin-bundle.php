<?php

namespace Dontdrinkandroot\CrudAdminBundle\Routing;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Action\CreateAction;
use Dontdrinkandroot\CrudAdminBundle\Action\DeleteAction;
use Dontdrinkandroot\CrudAdminBundle\Action\ListAction;
use Dontdrinkandroot\CrudAdminBundle\Action\ReadAction;
use Dontdrinkandroot\CrudAdminBundle\Action\UpdateAction;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class EntityLoader extends FileLoader
{
    const ATTRIBUTE_CONTROLLER = '_controller';

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'ddr_crud_admin_entity' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $path = $this->locator->locate($resource);

        $config = Yaml::parseFile($path);
        assert(isset($config['entity_class']), '"entity_class" must be set');
        $entityClass = $config['entity_class'];
        $namePrefix = $config['name_prefix'] ?? null;
        if (null === $namePrefix) {
            $tableizedName = ClassNameUtils::getTableizedShortName($entityClass);
            $namePrefix = 'ddr_crud_admin.' . $tableizedName . '.';
        }

        $routeCollection = new RouteCollection();

        $listRoute = new Route('/');
        $listRoute->addDefaults(
            [
                self::ATTRIBUTE_CONTROLLER      => ListAction::class,
                RequestAttributes::ENTITY_CLASS => $entityClass
            ]
        );
        $routeCollection->add('list', $listRoute);

        $createRoute = new Route('/__NEW__/edit');
        $createRoute->addDefaults(
            [
                self::ATTRIBUTE_CONTROLLER      => CreateAction::class,
                RequestAttributes::ENTITY_CLASS => $entityClass
            ]
        );
        $routeCollection->add('create', $createRoute);

        $updateRoute = new Route('/{id}/edit');
        $updateRoute->addDefaults(
            [
                self::ATTRIBUTE_CONTROLLER      => UpdateAction::class,
                RequestAttributes::ENTITY_CLASS => $entityClass
            ]
        );
        $routeCollection->add('update', $updateRoute);

        $deleteRoute = new Route('/{id}/delete');
        $deleteRoute->addDefaults(
            [
                self::ATTRIBUTE_CONTROLLER      => DeleteAction::class,
                RequestAttributes::ENTITY_CLASS => $entityClass
            ]
        );
        $routeCollection->add('delete', $deleteRoute);

        $readRoute = new Route('/{id}/read');
        $readRoute->addDefaults(
            [
                self::ATTRIBUTE_CONTROLLER      => ReadAction::class,
                RequestAttributes::ENTITY_CLASS => $entityClass
            ]
        );
        $routeCollection->add('read', $readRoute);

        $routeCollection->addNamePrefix($namePrefix);

        return $routeCollection;
    }
}
