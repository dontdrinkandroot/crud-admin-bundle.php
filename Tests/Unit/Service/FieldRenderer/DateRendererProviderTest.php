<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\FieldRenderer;

use DateTime;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\DateRendererProvider;
use PHPUnit\Framework\TestCase;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DateRendererProviderTest extends TestCase
{
    public function testRendering()
    {
        $renderer = new DateRendererProvider();
        $fieldDefinition = new FieldDefinition('path', 'Label', 'date');
        $value = new DateTime('2020-02-03');
        $this->assertTrue($renderer->supports($fieldDefinition, $value));
        $this->assertEquals('2020-02-03', $renderer->render($fieldDefinition, $value));
    }
}
