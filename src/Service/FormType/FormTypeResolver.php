<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormType;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlProviderInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @extends AbstractProviderService<FormTypeProviderInterface>
 */
class FormTypeResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param CrudOperation   $crudOperation
     * @param class-string<T> $entityClass
     * @param T|null          $entity
     *
     * @return ?class-string<FormTypeInterface>
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass, ?object $entity): ?string
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof FormTypeProviderInterface);
            if ($provider->supportsFormType($crudOperation, $entityClass, $entity)) {
                return $provider->provideFormType($crudOperation, $entityClass, $entity);
            }
        }

        return null;
    }

}
