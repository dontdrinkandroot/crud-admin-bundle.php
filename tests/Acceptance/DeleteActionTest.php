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
        $client = static::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        $exampleEntity = $referenceRepository->getReference('example-entity-1', ExampleEntity::class);
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);
        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testForbidden(): void
    {
        $client = static::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        self::logIn($client, 'user');
        $exampleEntity = $referenceRepository->getReference('example-entity-1', ExampleEntity::class);
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);
        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        self::assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $client = static::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        self::logIn($client, 'admin');
        $exampleEntity = $referenceRepository->getReference('example-entity-0', ExampleEntity::class);
        self::assertInstanceOf(ExampleEntity::class, $exampleEntity);

        $client->request('GET', '/example_entities/' . $exampleEntity->getId() . '/delete');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $client->submitForm('Confirm');
        self::assertResponseRedirects('/example_entities/');

        /* Test READ throws 404 */
        $crawler = $client->request('GET', '/example_entities/' . $exampleEntity->getId());
        self::assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }
}
