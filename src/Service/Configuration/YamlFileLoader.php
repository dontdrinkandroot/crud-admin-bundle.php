<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Configuration;

use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Yaml;

use function in_array;
use function is_string;

use const PATHINFO_EXTENSION;

class YamlFileLoader extends Loader
{
    private ?YamlParser $yamlParser = null;

    public function __construct(private readonly FileLocatorInterface $locator, string $env = null)
    {
        parent::__construct($env);
    }

    /**
     * {@inheritdoc}
     */
    public function load(mixed $resource, string $type = null): array
    {
        $path = Asserted::string($this->locator->locate($resource));

        return $this->loadFile($path);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(mixed $resource, string $type = null): bool
    {
        if (!is_string($resource)) {
            return false;
        }

        if (null === $type && in_array(pathinfo($resource, PATHINFO_EXTENSION), ['yaml', 'yml'], true)) {
            return true;
        }

        return in_array($type, ['yaml', 'yml'], true);
    }

    private function loadFile(string $file): array
    {
        if (!stream_is_local($file)) {
            throw new InvalidArgumentException(sprintf('This is not a local file "%s".', $file));
        }

        if (!is_file($file)) {
            throw new InvalidArgumentException(sprintf('The file "%s" does not exist.', $file));
        }

        if (null === $this->yamlParser) {
            $this->yamlParser = new YamlParser();
        }

        try {
            $configuration = $this->yamlParser->parseFile($file, Yaml::PARSE_CONSTANT | Yaml::PARSE_CUSTOM_TAGS);
        } catch (ParseException $e) {
            throw new InvalidArgumentException(
                sprintf('The file "%s" does not contain valid YAML: ', $file) . $e->getMessage(), 0, $e
            );
        }

        return $this->validate($configuration);
    }

    private function validate(array $configuration): array
    {
        return $configuration;
    }
}
