<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
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
     * @return ?class-string<FormTypeInterface>
     */
    public function resolve(string $entityClass): ?string
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof FormTypeProviderInterface);
            try {
                return $provider->provideFormType($entityClass);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }

}
