<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\FieldRenderer;

use DateTime;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\DateTimeRendererProvider;
use PHPUnit\Framework\TestCase;

class DateTimeRendererProviderTest extends TestCase
{
    public function testRendering(): void
    {
        $renderer = new DateTimeRendererProvider();
        $fieldDefinition = new FieldDefinition('path', 'datetime');
        $value = new DateTime('2020-02-03 04:05:06');
        $this->assertTrue($renderer->supports($fieldDefinition, $value));
        $this->assertEquals('2020-02-03 04:05:06', $renderer->render($fieldDefinition, $value));
    }
}
