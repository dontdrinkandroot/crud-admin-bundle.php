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
        $exampleEntity = $referenceRepository->getReference('example-entity-1');
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);

        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId());
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        self::logIn($client, 'user');
        $exampleEntity = $referenceRepository->getReference('example-entity-1');
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);

        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        /* Test expected values */
        $dds = $crawler->filter('dd');
        $this->assertCount(6, $dds);
        /* Id */
        $this->assertEquals('2', $dds->eq(0)->text(null, true));
        /* NullField */
        $this->assertEquals('requiredReadonly00001', $dds->eq(1)->text(null, true));
        /* RequiredField */
        $this->assertEquals('required00001', $dds->eq(2)->text(null, true));
    }

    public function testStandardRequestDepartment(): void
    {
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([DepartmentTwo::class]);
        self::logIn($client, 'user');
        $department = $referenceRepository->getReference(DepartmentTwo::class);
        self::assertInstanceOf(Department::class, $department);

        $crawler = $client->request('GET', '/deps/' . $department->getId());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        /* Test expected values */
        $dds = $crawler->filter('dd');
        $this->assertCount(3, $dds);
        /* Id */
        $this->assertEquals((string)$department->getId(), $dds->eq(0)->text(null, true));
        /* Name */
        $this->assertEquals('two', $dds->eq(1)->text(null, true));
        /* PhonePrefix*/
        $this->assertEquals('023', $dds->eq(2)->text(null, true));
    }
}
