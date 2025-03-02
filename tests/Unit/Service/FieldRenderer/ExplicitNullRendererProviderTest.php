<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\ExplicitNullRendererProvider;
use PHPUnit\Framework\TestCase;

class ExplicitNullRendererProviderTest extends TestCase
{
    public function testRendering(): void
    {
        $renderer = new ExplicitNullRendererProvider();
        $fieldDefinition = new FieldDefinition('path', 'date');
        $value = null;
        self::assertTrue($renderer->supports($fieldDefinition, $value));
        self::assertEquals('<em>null</em>', $renderer->render($fieldDefinition, $value));
    }
}
