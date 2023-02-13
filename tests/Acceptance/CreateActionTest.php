<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Repository\ExampleEntityRepository;
use Symfony\Component\HttpFoundation\Response;

class CreateActionTest extends AbstractTestCase
{
    public function testUnauthorized(): void
    {
        $this->loadClientAndFixtures();
        $crawler = $this->client->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testForbidden(): void
    {
        $this->loadClientAndFixtures();
        $this->logIn('user');
        $crawler = $this->client->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testValidationAndSubmission(): void
    {
        $this->loadClientAndFixtures();

        /* Test page is callable */
        $this->logIn('admin');
        $crawler = $this->client->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        /* Test validation is working */
        $crawler = $this->client->submitForm('Save', []);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $formGroups = $crawler->filter('form > div > div');
        $formGroupRequired = $formGroups->eq(0);
        $this->assertEquals('This value should not be blank.', $formGroupRequired->filter('ul li')->text());
        $formGroupRequired = $formGroups->eq(1);
        $this->assertEquals('This value should not be blank.', $formGroupRequired->filter('ul li')->text());

        /* Test submission is working */
        $crawler = $this->client->submitForm('Save', [
            'form[requiredReadonly]' => 'requiredReadonlyValue',
            'form[required]' => 'requiredValue',
        ]);
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/example_entities/');

        /* Test redirect to LIST page after submission */
        $crawler = $this->client->followRedirect();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        /* Test values are set as expected */
        $exampleEntityRepository = self::getContainer()->get(ExampleEntityRepository::class);
        self::assertInstanceOf(ExampleEntityRepository::class, $exampleEntityRepository);
        $entities = $exampleEntityRepository->findAll();
        self::assertCount(1, $entities);
        $entity = $entities[0];
        self::assertInstanceOf(ExampleEntity::class, $entity);
        self::assertEquals('requiredReadonlyValue', $entity->getRequiredReadonly());
        self::assertEquals('requiredValue', $entity->required);
        self::assertNull($entity->requiredNullable);
        self::assertEquals('defaultValue', $entity->requiredWithDefault);
        self::assertNull($entity->nullableWithDefault);
    }
}
