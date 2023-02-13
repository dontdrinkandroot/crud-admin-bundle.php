<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Symfony\Component\Form\FormTypeInterface;

class StaticFormTypeProvider implements FormTypeProviderInterface
{
    /**
     * @param class-string                    $entityClass
     * @param class-string<FormTypeInterface> $formType
     */
    public function __construct(private readonly string $entityClass, private readonly string $formType)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function provideFormType(string $entityClass): ?string
    {
        if ($entityClass !== $this->entityClass) {
            return null;
        }

        return $this->formType;
    }
}
