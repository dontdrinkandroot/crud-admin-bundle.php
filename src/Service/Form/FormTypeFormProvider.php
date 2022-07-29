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
    public function supportsForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): bool
    {
        return null !== $this->formTypeResolver->resolve($entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): FormInterface
    {
        $formType = Asserted::notNull($this->formTypeResolver->resolve($entityClass));

        $form = $this->formFactory->create($formType, $entity);

        $form->add(
            'submit',
            SubmitType::class,
            ['translation_domain' => 'DdrCrudAdmin']
        );

        return $form;
    }
}
