<?php

namespace Dontdrinkandroot\CrudAdminBundle\Serializer;

use Dontdrinkandroot\CrudAdminBundle\Model\Config\CrudConfig;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CrudConfigNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private readonly ObjectNormalizer $objectNormalizer)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return CrudConfig::class === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $resourceClass = array_key_first($data);
        $resourceData = $data[$resourceClass];

        $localContext = [
            AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [
                CrudConfig::class => ['resourceClass' => $resourceClass],
            ]
        ];
        $localContext = array_merge_recursive($context, $localContext);

        return $this->objectNormalizer->denormalize($resourceData, CrudConfig::class, $format, $localContext);
    }
}
