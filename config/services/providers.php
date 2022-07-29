<?php

namespace Dontdrinkandroot\CrudAdminBundle\Config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();
};
