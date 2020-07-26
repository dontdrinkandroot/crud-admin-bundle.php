<?php

namespace Acceptance;

use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\AbstractIntegrationTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CreateActionTest extends AbstractIntegrationTestCase
{
    public function testUnauthorized()
    {
        $this->loadKernelAndFixtures();
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testForbidden()
    {
        $this->loadKernelAndFixtures();
        $this->logIn('user');
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->kernelBrowser->getResponse()->getStatusCode());
    }

    public function testStandardRequest()
    {
        $this->loadKernelAndFixtures();
        $this->logIn('admin');
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());
    }
}
