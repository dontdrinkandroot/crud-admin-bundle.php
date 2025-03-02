<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\FieldRenderer;

use DateTime;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\DateRendererProvider;
use PHPUnit\Framework\TestCase;

class DateRendererProviderTest extends TestCase
{
    public function testRendering(): void
    {
        $renderer = new DateRendererProvider();
        $fieldDefinition = new FieldDefinition('path', 'date');
        $value = new DateTime('2020-02-03');
        self::assertTrue($renderer->supports($fieldDefinition, $value));
        self::assertEquals('2020-02-03', $renderer->render($fieldDefinition, $value));
    }
}
