<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormTypeFormProvider implements FormProviderInterface
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly FormTypeResolver $formTypeResolver,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): bool
    {
        return null !== $this->formTypeResolver->resolve($crudOperation, $entityClass, $entity);
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): FormInterface
    {
        $formType = Asserted::notNull($this->formTypeResolver->resolve($crudOperation, $entityClass, $entity));

        $form = $this->formFactory->create($formType, $entity);

        $form->add(
            'submit',
            SubmitType::class,
            ['translation_domain' => 'DdrCrudAdmin']
        );

        return $form;
    }
}
