<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\AbstractIntegrationTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\ExampleEntities;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends AbstractIntegrationTestCase
{
    public function testUnauthorized(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $this->loadKernelAndFixtures([ExampleEntities::class]);
        $this->logIn('user');
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/');
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());

        $rows = $crawler->filter('tr');
        $this->assertCount(11, $rows); /* Header + 10 Entities */
        /* Test Sorting was correct */
        $this->assertEquals('00000', $rows->eq(1)->filter('td')->eq(2)->text(null, true));
    }
}
