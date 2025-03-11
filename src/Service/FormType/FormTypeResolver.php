<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @template P of FormTypeProviderInterface
 * @extends AbstractProviderService<P>
 */
class FormTypeResolver extends AbstractProviderService implements FormTypeResolverInterface
{
    /**
     * @template T of object
     * @return class-string<FormTypeInterface<T>>|null
     */
    #[Override]
    public function resolveFormType(string $entityClass): ?string
    {
        foreach ($this->providers as $provider) {
            /** @var class-string<FormTypeInterface<T>>|null $formType */
            $formType = $provider->provideFormType($entityClass);
            if (null !== $formType) {
                return $formType;
            }
        }

        return null;
    }
}
