<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\AbstractIntegrationTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\ExampleEntities;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Symfony\Component\HttpFoundation\Response;

class UpdateActionTest extends AbstractIntegrationTestCase
{
    public function testUnauthorized(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId() . '/edit');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testForbidden(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $this->logIn('user');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId() . '/edit');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $this->logIn('admin');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);

        /* Test page is callable */
        $this->logIn('admin');
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/' . $exampleEntity->getId() . '/edit');
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());

        /* Test validation is working */
        $crawler = $this->kernelBrowser->submitForm('Submit', ['form[requiredField]' => null]);
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());
        $formGroups = $crawler->filter('form > div > div');
        $formGroupRequired = $formGroups->eq(1);
        $this->assertEquals('This value should not be blank.', $formGroupRequired->filter('ul li')->text(null, true));

        /* Test submission is working */
        $crawler = $this->kernelBrowser->submitForm('Submit', ['form[requiredField]' => 'ChangedValue']);
        $this->assertEquals(Response::HTTP_FOUND, $this->kernelBrowser->getResponse()->getStatusCode());
        $this->assertTrue(
            $this->kernelBrowser->getResponse()->isRedirect('/example_entities/' . $exampleEntity->getId())
        );

        /* Test redirect to READ page after submission */
        $crawler = $this->kernelBrowser->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());

        /* Test values are set as expected */
        $dds = $crawler->filter('dd');
        $this->assertCount(3, $dds);
        /* Id */
        $this->assertEquals('2', $dds->eq(0)->text(null, true));
        /* NullField */
        $this->assertEquals('', $dds->eq(1)->text(null, true));
        /* RequiredField */
        $this->assertEquals('ChangedValue', $dds->eq(2)->text(null, true));
    }
}
