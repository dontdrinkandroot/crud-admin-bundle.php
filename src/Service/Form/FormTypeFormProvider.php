<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeResolverInterface;
use Override;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @template T of object
 * @implements FormProviderInterface<T>
 */
class FormTypeFormProvider implements FormProviderInterface
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly FormTypeResolverInterface $formTypeResolver,
    ) {
    }

    #[Override]
    public function provideForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?FormInterface
    {
        $formType = $this->formTypeResolver->resolveFormType($entityClass);
        if (null === $formType) {
            return null;
        }

        /** @var FormInterface<T> $form */
        /** @phpstan-ignore varTag.type */
        $form = $this->formFactory->create($formType, $entity);

        $form->add(
            'submit',
            SubmitType::class,
            ['label' => 'submit', 'translation_domain' => 'DdrCrudAdmin']
        );

        return $form;
    }
}
