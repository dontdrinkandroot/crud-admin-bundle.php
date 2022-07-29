<?php

namespace Dontdrinkandroot\CrudAdminBundle\Serializer;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\Config\CrudConfig;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CrudConfigDenormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private readonly ObjectNormalizer $objectNormalizer)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return CrudConfig::class === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): CrudConfig
    {
        $entityClass = Asserted::string(array_key_first($data), 'Could not find EntityClass');
        $resourceData = $data[$entityClass];

        $localContext = [
            AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [
                CrudConfig::class => ['entityClass' => $entityClass],
            ]
        ];
        $localContext = array_merge_recursive($context, $localContext);

        return $this->objectNormalizer->denormalize($resourceData, CrudConfig::class, $format, $localContext);
    }
}
