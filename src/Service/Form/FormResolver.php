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
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     */
    public function resolveForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): ?FormInterface
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->provideForm($entityClass, $crudOperation, $entity);
            } catch (UnsupportedByProviderException) {
                /* Continue */
            }
        }

        return null;
    }
}
