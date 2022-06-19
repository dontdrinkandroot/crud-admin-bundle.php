<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\FieldRenderer;

use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\ExplicitNullRendererProvider;
use PHPUnit\Framework\TestCase;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ExplicitNullRendererProviderTest extends TestCase
{
    public function testRendering()
    {
        $renderer = new ExplicitNullRendererProvider();
        $fieldDefinition = new FieldDefinition('path', 'date');
        $value = null;
        $this->assertTrue($renderer->supports($fieldDefinition, $value));
        $this->assertEquals('<em>null</em>', $renderer->render($fieldDefinition, $value));
    }
}
