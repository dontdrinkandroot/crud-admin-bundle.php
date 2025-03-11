<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Override;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @template T of object
 * @implements FormTypeProviderInterface<T>
 */
class StaticFormTypeProvider implements FormTypeProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @param class-string<FormTypeInterface<T>> $formType
     */
    public function __construct(private readonly string $entityClass, private readonly string $formType)
    {
    }

    #[Override]
    public function provideFormType(string $entityClass): ?string
    {
        if ($entityClass !== $this->entityClass) {
            return null;
        }

        return $this->formType;
    }
}
