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

    public function testValidationAndSubmission()
    {
        $this->loadKernelAndFixtures();

        /* Test page is callable */
        $this->logIn('admin');
        $crawler = $this->kernelBrowser->request('GET', '/example_entities/__NEW__/edit');
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());

        /* Test validation is working */
        $crawler = $this->kernelBrowser->submitForm('Submit', []);
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());
        $formGroups = $crawler->filter('form > div > div');
        $formGroupRequired = $formGroups->eq(1);
        $this->assertEquals('This value should not be blank.', $formGroupRequired->filter('ul li')->text(null, true));

        /* Test submission is working */
        $crawler = $this->kernelBrowser->submitForm('Submit', ['form[requiredField]' => 'TestValue']);
        $this->assertEquals(Response::HTTP_FOUND, $this->kernelBrowser->getResponse()->getStatusCode());
        $this->assertTrue($this->kernelBrowser->getResponse()->isRedirect('/example_entities/1'));

        /* Test redirect to READ page after submission */
        $crawler = $this->kernelBrowser->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->kernelBrowser->getResponse()->getStatusCode());

        /* Test values are set as expected */
        $dds = $crawler->filter('dd');
        $this->assertCount(3, $dds);
        /* Id */
        $this->assertEquals('1', $dds->eq(0)->text(null, true));
        /* NullField */
        $this->assertEquals('', $dds->eq(1)->text(null, true));
        /* RequiredField */
        $this->assertEquals('TestValue', $dds->eq(2)->text(null, true));
    }
}
