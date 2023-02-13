<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\ExampleEntities;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Symfony\Component\HttpFoundation\Response;

class UpdateActionTest extends AbstractTestCase
{
    public function testUnauthorized(): void
    {
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/edit');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testForbidden(): void
    {
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $this->logIn('user');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);
        $crawler = $this->client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/edit');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $this->logIn('admin');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        assert($exampleEntity instanceof ExampleEntity);

        /* Test page is callable */
        $this->logIn('admin');
        $crawler = $this->client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/edit');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        /* Test validation is working */
        $crawler = $this->client->submitForm('Save', ['form[required]' => null]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $formGroups = $crawler->filter('form > div > div');
        $formGroupRequired = $formGroups->eq(1);
        $this->assertEquals('This value should not be blank.', $formGroupRequired->filter('ul li')->text(null, true));

        /* Test submission is working */
        $crawler = $this->client->submitForm('Save', ['form[required]' => 'ChangedValue']);
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/example_entities/');

        /* Test redirect to LIST page after submission */
        $crawler = $this->client->followRedirect();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $trs = $crawler->filter('tbody tr');
        $this->assertCount(10, $trs);

        /* Test values are set as expected */
        $crawler = $this->client->request('GET', '/example_entities/' . $exampleEntity->getId());
        self::assertResponseStatusCodeSame(200);
        $dds = $crawler->filter('dd');
        $this->assertCount(6, $dds);
        $this->assertEquals($exampleEntity->getId(), $dds->eq(0)->text(null, true));
        $this->assertEquals('requiredReadonly00001', $dds->eq(1)->text(null, true));
        $this->assertEquals('ChangedValue', $dds->eq(2)->text(null, true));
    }
}
