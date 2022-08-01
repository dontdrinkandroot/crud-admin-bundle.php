<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Controller;

use Dontdrinkandroot\CrudAdminBundle\Controller\CrudController;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;

/**
 * @extends CrudController<ExampleEntity>
 */
class ExampleEntityController extends CrudController
{
    public function __construct()
    {
        parent::__construct(ExampleEntity::class);
    }
}
