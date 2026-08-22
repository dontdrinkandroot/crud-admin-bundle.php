<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\FieldDefinition;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\DoctrineFieldDefinitionsProvider;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;
use PHPUnit\Framework\TestCase;

class DoctrineFieldDefinitionsProviderTest extends TestCase
{
    public function testProvideFieldDefinitions(): void
    {
        $classMetadata = new ClassMetadata(Department::class);
        $classMetadata->setPrimaryTable(['name' => 'foo']);
        $classMetadata->mapField(['fieldName' => 'id', 'type' => 'integer', 'id' => true]);
        $classMetadata->mapField(['fieldName' => 'name', 'type' => 'string']);
        $classMetadata->mapField(['fieldName' => 'amount', 'type' => 'decimal']);
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_AUTO);

        $entityManager = self::createStub(EntityManagerInterface::class);
        $entityManager->method('getClassMetadata')->willReturn($classMetadata);

        $managerRegistry = self::createStub(ManagerRegistry::class);
        $managerRegistry->method('getManagerForClass')->willReturn($entityManager);

        $provider = new DoctrineFieldDefinitionsProvider($managerRegistry);

        $fieldDefinitions = $provider->provideFieldDefinitions(Department::class);

        self::assertNotNull($fieldDefinitions);
        self::assertCount(3, $fieldDefinitions);

        $idDefinition = $fieldDefinitions['id'];
        self::assertInstanceOf(FieldDefinition::class, $idDefinition);
        self::assertEquals([CrudOperation::LIST, CrudOperation::READ], $idDefinition->crudOperations);

        $nameDefinition = $fieldDefinitions['name'];
        self::assertEquals('string', $nameDefinition->displayType);
        self::assertTrue($nameDefinition->sortable);
        self::assertTrue($nameDefinition->filterable);
        self::assertEquals(CrudOperation::all(), $nameDefinition->crudOperations);

        $amountDefinition = $fieldDefinitions['amount'];
        self::assertEquals('decimal', $amountDefinition->displayType);
        self::assertFalse($amountDefinition->filterable);
    }

    public function testProvideFieldDefinitionsWithoutManager(): void
    {
        $managerRegistry = self::createStub(ManagerRegistry::class);
        $managerRegistry->method('getManagerForClass')->willReturn(null);

        $provider = new DoctrineFieldDefinitionsProvider($managerRegistry);

        self::assertNull($provider->provideFieldDefinitions(Department::class));
    }
}