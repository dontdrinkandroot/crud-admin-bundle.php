<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @extends AbstractProviderService<FormTypeProviderInterface>
 */
class FormTypeResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return class-string<FormTypeInterface>|null
     */
    public function resolveFormType(string $entityClass): ?string
    {
        foreach ($this->providers as $provider) {
            $formType = $provider->provideFormType($entityClass);
            if (null !== $formType) {
                return $formType;
            }
        }

        return null;
    }

}
