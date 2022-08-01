<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Form\Type;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Department::class);
        $resolver->setDefault('empty_data', function (FormInterface $form) {
            return new Department(
                Asserted::string($form->get('name')->getData())
            );
        });
    }
}
