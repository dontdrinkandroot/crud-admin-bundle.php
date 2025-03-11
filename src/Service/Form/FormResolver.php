<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;
use Symfony\Component\Form\FormInterface;

/**
 * @template P of FormProviderInterface
 * @extends AbstractProviderService<P>
 */
class FormResolver extends AbstractProviderService implements FormResolverInterface
{
    /**
     * @template T of object
     * @return FormInterface<T>|null
     */
    #[Override]
    public function resolveForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): ?FormInterface
    {
        foreach ($this->providers as $provider) {
            /** @var FormInterface<T>|null $form */
            $form = $provider->provideForm($entityClass, $crudOperation, $entity);
            if (null !== $form) {
                return $form;
            }
        }

        return null;
    }
}
