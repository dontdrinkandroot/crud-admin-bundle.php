<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\NullRendererProvider;
use PHPUnit\Framework\TestCase;

class NullRendererProviderTest extends TestCase
{
    public function testRendering(): void
    {
        $renderer = new NullRendererProvider();
        $fieldDefinition = new FieldDefinition('path', 'json');

        $value = null;
        self::assertTrue($renderer->supports($fieldDefinition, $value));
        self::assertEquals('', $renderer->render($fieldDefinition, $value));
    }
}
