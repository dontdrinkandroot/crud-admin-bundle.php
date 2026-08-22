<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\DefaultTemplateProvider;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;
use PHPUnit\Framework\TestCase;

class DefaultTemplateProviderTest extends TestCase
{
    public function testProvideTemplate(): void
    {
        $provider = new DefaultTemplateProvider();

        self::assertEquals('@DdrCrudAdmin/list.html.twig', $provider->provideTemplate(Department::class, CrudOperation::LIST));
        self::assertEquals('@DdrCrudAdmin/read.html.twig', $provider->provideTemplate(Department::class, CrudOperation::READ));
        self::assertEquals('@DdrCrudAdmin/update.html.twig', $provider->provideTemplate(Department::class, CrudOperation::CREATE));
        self::assertEquals('@DdrCrudAdmin/update.html.twig', $provider->provideTemplate(Department::class, CrudOperation::UPDATE));
        self::assertEquals('@DdrCrudAdmin/delete.html.twig', $provider->provideTemplate(Department::class, CrudOperation::DELETE));
    }
}