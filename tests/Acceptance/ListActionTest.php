<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\DepartmentOne;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\DepartmentTwo;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures\ExampleEntities;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends AbstractTestCase
{
    public function testUnauthorized(): void
    {
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $crawler = $this->client->request('GET', '/example_entities/');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $this->loadClientAndFixtures([ExampleEntities::class]);
        $this->logIn('user');
        $crawler = $this->client->request('GET', '/example_entities/');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $rows = $crawler->filter('tr');
        $this->assertCount(11, $rows); /* Header + 10 Entities */
        /* Test Sorting was correct */
        $this->assertEquals('required00000', $rows->eq(1)->filter('td')->eq(2)->text(null, true));
    }

    public function testStandardRequestDepartment(): void
    {
        $this->loadClientAndFixtures([DepartmentOne::class, DepartmentTwo::class]);
        $this->logIn('user');

        $crawler = $this->client->request('GET', '/deps/');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        self::assertEquals('Overrridden - Departments', $crawler->filter('title')->text());

        $rows = $crawler->filter('tr');
        $this->assertCount(3, $rows); /* Header + 2 Entity */

        $cols = $rows->eq(0)->filter('th');
        self::assertCount(2, $cols);
        $this->assertEquals('Name', $cols->eq(0)->text());

        /* Test Sorting was correct */
        self::assertCount(2, $cols);
        $this->assertEquals('two', $rows->eq(1)->filter('td')->eq(0)->text());
        $this->assertEquals('one', $rows->eq(2)->filter('td')->eq(0)->text());
    }
}
