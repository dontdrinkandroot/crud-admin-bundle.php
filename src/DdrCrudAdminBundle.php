<?php

namespace Dontdrinkandroot\CrudAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrCrudAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
