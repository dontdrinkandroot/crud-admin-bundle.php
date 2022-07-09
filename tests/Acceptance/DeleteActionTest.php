<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\AbstractIntegrationTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\ExampleEntities;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Symfony\Component\HttpFoundation\Response;

class DeleteActionTest extends AbstractIntegrationTestCase
{
    public function testUnauthorized(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testForbidden(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $this->logIn('user');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $this->logIn('admin');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-0');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        $this->assertEquals(Response::HTTP_FOUND, $this->kernelBrowser->getResponse()->getStatusCode());
        $this->assertTrue($this->kernelBrowser->getResponse()->isRedirect('/example_entities/'));

        /* Test READ throws 404 */
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId());
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->kernelBrowser->getResponse()->getStatusCode());
    }
}