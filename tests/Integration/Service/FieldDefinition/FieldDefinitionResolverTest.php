<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Integration\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;

class FieldDefinitionResolverTest extends AbstractTestCase
{
    public function testResolveByDefault(): void
    {
        $fieldDefinitionResolver = self::getService(FieldDefinitionsResolverInterface::class);
        $fieldDefinitions = $fieldDefinitionResolver->resolveFieldDefinitions(
            ExampleEntity::class,
            CrudOperation::CREATE
        );

        self::assertNotNull($fieldDefinitions);
        self::assertCount(5, $fieldDefinitions);
    }

    public function testResolveByYamlConfig(): void
    {
        $fieldDefinitionResolver = self::getService(FieldDefinitionsResolverInterface::class);
        $fieldDefinitions = $fieldDefinitionResolver->resolveFieldDefinitions(
            Department::class,
            CrudOperation::READ
        );

        self::assertNotNull($fieldDefinitions);
        self::assertCount(3, $fieldDefinitions);
    }
}
