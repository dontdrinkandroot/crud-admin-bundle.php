<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config\Routes;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import('.', 'ddr_crud');
};
