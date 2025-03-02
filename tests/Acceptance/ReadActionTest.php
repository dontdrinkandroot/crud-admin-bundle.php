<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\DepartmentTwo;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\ExampleEntities;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Symfony\Component\HttpFoundation\Response;

class ReadActionTest extends AbstractTestCase
{
    public function testUnauthorized(): void
    {
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        $exampleEntity = $referenceRepository->getReference('example-entity-1', ExampleEntity::class);
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);

        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId());
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        self::logIn($client, 'user');
        $exampleEntity = $referenceRepository->getReference('example-entity-1', ExampleEntity::class);
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);

        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId());
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        /* Test expected values */
        $dds = $crawler->filter('dd');
        self::assertCount(6, $dds);
        /* Id */
        self::assertEquals('2', $dds->eq(0)->text(null, true));
        /* NullField */
        self::assertEquals('requiredReadonly00001', $dds->eq(1)->text());
        /* RequiredField */
        self::assertEquals('required00001', $dds->eq(2)->text());
    }

    public function testStandardRequestDepartment(): void
    {
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([DepartmentTwo::class]);
        self::logIn($client, 'user');
        $department = $referenceRepository->getReference(DepartmentTwo::class, Department::class);
        self::assertInstanceOf(Department::class, $department);

        $crawler = $client->request('GET', '/deps/' . $department->getId());
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        /* Test expected values */
        $dds = $crawler->filter('dd');
        self::assertCount(3, $dds);
        /* Id */
        self::assertEquals((string)$department->getId(), $dds->eq(0)->text(null, true));
        /* Name */
        self::assertEquals('two', $dds->eq(1)->text());
        /* PhonePrefix*/
        self::assertEquals('023', $dds->eq(2)->text());
    }
}
