<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Symfony\Component\Form\FormInterface;

/**
 * @extends AbstractProviderService<FormProviderInterface>
 */
class FormResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return ?FormInterface
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass, ?object $entity): ?FormInterface
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof FormProviderInterface);
            try {
                return $provider->provideForm($entityClass, $crudOperation, $entity);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
