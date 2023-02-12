<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\AbstractIntegrationTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Repository\ExampleEntityRepository;
use Symfony\Component\HttpFoundation\Response;

class CreateActionTest extends AbstractIntegrationTestCase
{
    public function testUnauthorized(): void
    {
        $this->loadKernelAndFixtures();
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testForbidden(): void
    {
        $this->loadKernelAndFixtures();
        $this->logIn('user');
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testValidationAndSubmission(): void
    {
        $this->loadKernelAndFixtures();

        /* Test page is callable */
        $this->logIn('admin');
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());

        /* Test validation is working */
        $crawler = $this->kernelBrowser->submitForm('Save', []);
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());
        $formGroups = $crawler->filter('form > div > div');
        $formGroupRequired = $formGroups->eq(0);
        $this->assertEquals('This value should not be blank.', $formGroupRequired->filter('ul li')->text(null, true));
        $formGroupRequired = $formGroups->eq(1);
        $this->assertEquals('This value should not be blank.', $formGroupRequired->filter('ul li')->text(null, true));

        /* Test submission is working */
        $crawler = $this->kernelBrowser->submitForm('Save', [
            'form[requiredReadonly]' => 'requiredReadonlyValue',
            'form[required]' => 'requiredValue',
        ]);
        $this->assertEquals(Response::HTTP_FOUND, $this->kernelBrowser->getResponse()->getStatusCode());
        self::assertResponseRedirects('/example_entities/');

        /* Test redirect to LIST page after submission */
        $crawler = $this->kernelBrowser->followRedirect();
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
