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
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        $exampleEntity = $referenceRepository->getReference('example-entity-1', ExampleEntity::class);
        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/edit');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testForbidden(): void
    {
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        self::logIn($client, 'user');
        $exampleEntity = $referenceRepository->getReference('example-entity-1', ExampleEntity::class);
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);
        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/edit');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        self::logIn($client, 'admin');
        $exampleEntity = $referenceRepository->getReference('example-entity-1', ExampleEntity::class);

        /* Test page is callable */
        self::logIn($client, 'admin');
        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/edit');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        /* Test validation is working */
        $crawler = $client->submitForm('Save', ['form[required]' => null]);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $formGroups = $crawler->filter('form > div > div');
        $formGroupRequired = $formGroups->eq(1);
        $this->assertEquals('This value should not be blank.', $formGroupRequired->filter('ul li')->text(null, true));

        /* Test submission is working */
        $crawler = $client->submitForm('Save', ['form[required]' => 'ChangedValue']);
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/example_entities/');

        /* Test redirect to LIST page after submission */
        $crawler = $client->followRedirect();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $trs = $crawler->filter('tbody tr');
        $this->assertCount(10, $trs);

        /* Test values are set as expected */
        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId());
        self::assertResponseStatusCodeSame(200);
        $dds = $crawler->filter('dd');
        $this->assertCount(6, $dds);
        $this->assertEquals($exampleEntity->getId(), $dds->eq(0)->text(null, true));
        $this->assertEquals('requiredReadonly00001', $dds->eq(1)->text(null, true));
        $this->assertEquals('ChangedValue', $dds->eq(2)->text(null, true));
    }
}
