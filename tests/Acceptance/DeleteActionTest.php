<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\ExampleEntities;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Symfony\Component\HttpFoundation\Response;

class DeleteActionTest extends AbstractTestCase
{
    public function testUnauthorized(): void
    {
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);
        $crawler = $this->client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testForbidden(): void
    {
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $this->logIn('user');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-1');
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);
        $crawler = $this->client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $this->logIn('admin');
        $exampleEntity = $this->referenceRepository->getReference('example-entity-0');
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);

        $this->client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->client->submitForm('Confirm');
        self::assertResponseRedirects('/example_entities/');

        /* Test READ throws 404 */
        $crawler = $this->client->request('GET', '/example_entities/' . $exampleEntity->getId());
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }
}
