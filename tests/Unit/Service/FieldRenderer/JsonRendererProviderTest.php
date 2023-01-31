<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\JsonRendererProvider;
use PHPUnit\Framework\TestCase;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class JsonRendererProviderTest extends TestCase
{
    public function testRendering(): void
    {
        $renderer = new JsonRendererProvider();
        $fieldDefinition = new FieldDefinition('path', 'json');

        $value = ['a', 'b' => ['c', 1 => 'foo']];
        $this->assertTrue($renderer->supports($fieldDefinition, $value));
        $this->assertEquals(
            '{&quot;0&quot;:&quot;a&quot;,&quot;b&quot;:[&quot;c&quot;,&quot;foo&quot;]}',
            $renderer->render($fieldDefinition, $value)
        );

        $value = ['ROLE_USER', 'ROLE_ADMIN'];
        $this->assertTrue($renderer->supports($fieldDefinition, $value));
        $this->assertEquals(
            '[&quot;ROLE_USER&quot;,&quot;ROLE_ADMIN&quot;]',
            $renderer->render($fieldDefinition, $value)
        );
    }
}
