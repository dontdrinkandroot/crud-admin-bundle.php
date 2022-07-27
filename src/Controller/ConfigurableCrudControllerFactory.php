<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\CrudAdminBundle\Model\Config\CrudConfig;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ConfigurableCrudControllerFactory
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function create(string $configFile): ConfigurableCrudController
    {
        $yaml = file_get_contents($configFile);
        $crudConfig = $this->serializer->deserialize(
            $yaml,
            CrudConfig::class,
            'yaml',
            [AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false]
        );

        return new ConfigurableCrudController($crudConfig);
    }
}
