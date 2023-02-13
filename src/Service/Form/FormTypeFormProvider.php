<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

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
    public function provideForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?FormInterface
    {
        $formType = $this->formTypeResolver->resolveFormType($entityClass);
        if (null === $formType) {
            return null;
        }

        $form = $this->formFactory->create($formType, $entity);

        $form->add(
            'submit',
            SubmitType::class,
            ['label' => 'submit', 'translation_domain' => 'DdrCrudAdmin']
        );

        return $form;
    }
}
