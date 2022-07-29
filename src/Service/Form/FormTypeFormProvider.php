<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
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
    public function provideForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): FormInterface
    {
        $formType = $this->formTypeResolver->resolveFormType($entityClass);
        if (null === $formType) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation, $entity);
        }

        $form = $this->formFactory->create($formType, $entity);

        $form->add(
            'submit',
            SubmitType::class,
            ['translation_domain' => 'DdrCrudAdmin']
        );

        return $form;
    }
}
