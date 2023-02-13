<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
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
     * @param T|null $entity
     *
     */
    public function resolveForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): ?FormInterface
    {
        foreach ($this->providers as $provider) {
            $form = $provider->provideForm($entityClass, $crudOperation, $entity);
            if (null !== $form) {
                return $form;
            }
        }

        return null;
    }
}
