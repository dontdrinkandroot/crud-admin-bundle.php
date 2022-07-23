<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Controller;

use Dontdrinkandroot\CrudAdminBundle\Controller\AbstractCrudController;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;

class ExampleEntityController extends AbstractCrudController
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return ExampleEntity::class;
    }
}
