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
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);

        $crawler = $this->client->request('GET', '/example_entities/' . $exampleEntity->getId());
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $this->logIn('user');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);

        $crawler = $this->client->request('GET', '/example_entities/' . $exampleEntity->getId());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

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
        $this->loadClientAndFixtures([DepartmentTwo::class]);
        $this->logIn('user');
        $department = $this->referenceRepository->getReference(DepartmentTwo::class);
        self::assertInstanceOf(Department::class, $department);

        $crawler = $this->client->request('GET', '/deps/' . $department->id);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        /* Test expected values */
        $dds = $crawler->filter('dd');
        $this->assertCount(3, $dds);
        /* Id */
        $this->assertEquals((string)$department->id, $dds->eq(0)->text(null, true));
        /* Name */
        $this->assertEquals('two', $dds->eq(1)->text(null, true));
        /* PhonePrefix*/
        $this->assertEquals('023', $dds->eq(2)->text(null, true));
    }
}
