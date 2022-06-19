<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\NullRendererProvider;
use PHPUnit\Framework\TestCase;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class NullRendererProviderTest extends TestCase
{
    public function testRendering()
    {
        $renderer = new NullRendererProvider();
        $fieldDefinition = new FieldDefinition('path', 'json');

        $value = null;
        $this->assertTrue($renderer->supports($fieldDefinition, $value));
        $this->assertEquals('', $renderer->render($fieldDefinition, $value));
    }
}
