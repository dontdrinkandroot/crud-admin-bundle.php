<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\LabelService;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class DoctrineFormProvider implements FormProviderInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly FormFactoryInterface $formFactory,
        private readonly TranslationDomainResolverInterface $translationDomainResolver,
        private readonly LabelService $fieldDefinitionLabelService
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(string $entityClass, CrudOperation $crudOperation, ?object $entity): FormInterface
    {
        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation, $entityManager);
        }

        $classMetadata = $entityManager->getClassMetadata($entityClass);
        $fields = array_keys($classMetadata->fieldMappings);

        $formBuilder = $this->formFactory->createBuilder(
            FormType::class,
            $entity,
            ['data_class' => $entityClass]
        );

        $translationDomain = $this->translationDomainResolver->resolveTranslationDomain($entityClass);

        foreach ($fields as $field) {
            $fieldMapping = $classMetadata->fieldMappings[$field];
            $fieldName = $fieldMapping['fieldName'];
            if (!array_key_exists('id', $fieldMapping) || false === $fieldMapping['id']) {
                $formBuilder->add(
                    $fieldName,
                    null,
                    [
                        'label' => $this->fieldDefinitionLabelService->getLabel($fieldName),
                        'translation_domain' => $translationDomain
                    ]
                );
            }
        }

        $formBuilder->add(
            'submit',
            SubmitType::class,
            ['translation_domain' => 'DdrCrudAdmin']
        );

        return $formBuilder->getForm();
    }
}
