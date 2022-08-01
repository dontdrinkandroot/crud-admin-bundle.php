<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\AbstractIntegrationTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\DepartmentTwo;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\ExampleEntities;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Symfony\Component\HttpFoundation\Response;

class ReadActionTest extends AbstractIntegrationTestCase
{
    public function testUnauthorized(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId());
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $this->logIn('user');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);

        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId());
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());

        /* Test expected values */
        $dds = $crawler->filter('dd');
        $this->assertCount(3, $dds);
        /* Id */
        $this->assertEquals('2', $dds->eq(0)->text(null, true));
        /* NullField */
        $this->assertEquals('', $dds->eq(1)->text(null, true));
        /* RequiredField */
        $this->assertEquals('00001', $dds->eq(2)->text(null, true));
    }

    public function testStandardRequestDepartment(): void
    {
        $this->loadKernelAndFixtures([DepartmentTwo::class]);
        $this->logIn('user');
        $department = $this->referenceRepository->getReference(DepartmentTwo::class);
        assert($department instanceof DepartmentTwo);

        $crawler = $this->kernelBrowser->request('GET', '/deps/' . $department->id);
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());

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
