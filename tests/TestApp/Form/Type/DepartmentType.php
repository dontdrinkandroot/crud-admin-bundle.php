<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Form\Type;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<Department>
 */
class DepartmentType extends AbstractType
{
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name');
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Department::class);
        $resolver->setDefault('empty_data', fn(FormInterface $form): Department => new Department(
            Asserted::string($form->get('name')->getData())
        ));
    }
}
