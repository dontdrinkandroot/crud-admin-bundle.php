<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
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
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return ?FormInterface
     */
    public function resolve(string $crudOperation, string $entityClass, ?object $entity): ?FormInterface
    {
        foreach ($this->getProviders() as $provider) {
            assert($provider instanceof FormProviderInterface);
            if ($provider->supportsForm($crudOperation, $entityClass, $entity)) {
                return $provider->provideForm($crudOperation, $entityClass, $entity);
            }
        }

        return null;
    }
}
