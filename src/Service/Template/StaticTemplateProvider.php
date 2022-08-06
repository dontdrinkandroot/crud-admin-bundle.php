<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;

class StaticTemplateProvider implements TemplateProviderInterface
{
    /**
     * @param class-string $entityClass
     * @param array<string, string>  $templates
     */
    public function __construct(private readonly string $entityClass, private readonly array $templates)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): string
    {
        if (
            $entityClass !== $this->entityClass
            || null === ($template = $this->getTemplate($crudOperation))
        ) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        return $template;
    }

    private function getTemplate(CrudOperation $crudOperation): ?string
    {
        $key = $crudOperation->value;
        if (array_key_exists($key, $this->templates)) {
            return $this->templates[$key];
        }

        $key = strtolower($key);
        return $this->templates[$key] ?? null;
    }
}
