<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\LabelService;
use Dontdrinkandroot\CrudAdminBundle\Service\ReflectionDataMapper;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Override;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FieldDefinitionsFormProvider implements FormProviderInterface
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly TranslationDomainResolverInterface $translationDomainResolver,
        private readonly LabelService $fieldDefinitionLabelService,
        private readonly FieldDefinitionsResolverInterface $fieldDefinitionsResolver
    ) {
    }

    #[Override]
    public function provideForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?FormInterface
    {
        $fieldDefinitions = $this->fieldDefinitionsResolver->resolveFieldDefinitions($entityClass, $crudOperation);
        if (null === $fieldDefinitions) {
            return null;
        }

        $dataMapper = new ReflectionDataMapper($entityClass);
        $formBuilder = $this->formFactory->createBuilder(
            FormType::class,
            $entity,
            [
                'data_class' => $entityClass,
                'empty_data' => $dataMapper->getInstantiator()
            ]
        );
        $formBuilder->setDataMapper($dataMapper);

        $translationDomain = $this->translationDomainResolver->resolveTranslationDomain($entityClass);

        foreach ($fieldDefinitions as $fieldDefinition) {
            $options = [
                'label' => $this->fieldDefinitionLabelService->getLabel($fieldDefinition->propertyPath),
                'translation_domain' => $translationDomain
            ];
            $formBuilder->add(
                $fieldDefinition->propertyPath,
                $fieldDefinition->formType,
                $options
            );
        }

        $formBuilder->add(
            'submit',
            SubmitType::class,
            ['label' => 'submit', 'translation_domain' => 'DdrCrudAdmin']
        );

        return $formBuilder->getForm();
    }
}
