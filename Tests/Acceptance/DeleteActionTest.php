<?php

namespace Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\AbstractIntegrationTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\ExampleEntities;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DeleteActionTest extends AbstractIntegrationTestCase
{
    public function testUnauthorized()
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testForbidden()
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $this->logIn('user');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testStandardRequest()
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $this->logIn('admin');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        $this->assertEquals(Response::HTTP_FOUND, $this->kernelBrowser->getResponse()->getStatusCode());
    }
}
