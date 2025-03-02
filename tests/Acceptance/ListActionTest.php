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
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        $crawler = $client->request('GET', '/example_entities/');
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testStandardRequest(): void
    {
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([ExampleEntities::class]);
        self::logIn($client, 'user');
        $crawler = $client->request('GET', '/example_entities/');
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $rows = $crawler->filter('tr');
        self::assertCount(11, $rows); /* Header + 10 Entities */
        /* Test Sorting was correct */
        self::assertEquals('required00000', $rows->eq(1)->filter('td')->eq(2)->text(null, true));
    }

    public function testStandardRequestDepartment(): void
    {
        $client = self::createClient();
        $referenceRepository = self::loadFixtures([DepartmentOne::class, DepartmentTwo::class]);
        self::logIn($client, 'user');

        $crawler = $client->request('GET', '/deps/');
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        self::assertEquals('Overrridden - Departments', $crawler->filter('title')->text());

        $rows = $crawler->filter('tr');
        self::assertCount(3, $rows); /* Header + 2 Entity */

        $cols = $rows->eq(0)->filter('th');
        self::assertCount(2, $cols);
        self::assertEquals('Name', $cols->eq(0)->text());

        /* Test Sorting was correct */
        self::assertCount(2, $cols);
        self::assertEquals('two', $rows->eq(1)->filter('td')->eq(0)->text());
        self::assertEquals('one', $rows->eq(2)->filter('td')->eq(0)->text());
    }
}
