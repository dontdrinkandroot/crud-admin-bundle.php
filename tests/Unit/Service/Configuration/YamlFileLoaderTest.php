<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Unit\Service\Configuration;

use Dontdrinkandroot\CrudAdminBundle\Service\Configuration\YamlFileLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class YamlFileLoaderTest extends TestCase
{
    public function testLoad(): void
    {
        $loader = new YamlFileLoader(new FileLocator(__DIR__ . '/fixtures'));

        $config = $loader->load('valid.yaml');

        self::assertEquals(['App\Entity\Foo' => ['form_type' => 'App\Form\FooType']], $config);
    }

    public function testLoadWithType(): void
    {
        $loader = new YamlFileLoader(new FileLocator(__DIR__ . '/fixtures'));

        $config = $loader->load('valid.yml', 'yaml');

        self::assertEquals(['App\Entity\Foo' => ['form_type' => 'App\Form\FooType']], $config);
    }

    public function testLoadInvalidYaml(): void
    {
        $loader = new YamlFileLoader(new FileLocator(__DIR__ . '/fixtures'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('does not contain valid YAML');

        $loader->load('invalid.yaml');
    }

    public function testLoadMissingFile(): void
    {
        $loader = new YamlFileLoader(new FileLocator(__DIR__ . '/fixtures'));

        $this->expectException(FileLocatorFileNotFoundException::class);

        $loader->load('missing.yaml');
    }

    public function testSupports(): void
    {
        $loader = new YamlFileLoader(new FileLocator(__DIR__ . '/fixtures'));

        self::assertTrue($loader->supports('foo.yaml'));
        self::assertTrue($loader->supports('foo.yml'));
        self::assertFalse($loader->supports('foo.json'));
        self::assertFalse($loader->supports(123));
        self::assertTrue($loader->supports('foo.txt', 'yaml'));
        self::assertTrue($loader->supports('foo.txt', 'yml'));
        self::assertFalse($loader->supports('foo.txt', 'json'));
    }
}