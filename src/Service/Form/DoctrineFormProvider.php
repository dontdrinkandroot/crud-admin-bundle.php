<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class DoctrineFormProvider implements FormProviderInterface
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private FormFactoryInterface $formFactory,
        private ItemResolver $itemResolver,
        private TranslationDomainResolver $translationDomainResolver
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(CrudOperation $crudOperation, string $entityClass, ?object $entity): ?FormInterface
    {
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        $classMetadata = $entityManager->getClassMetadata($entityClass);
        $formBuilder = $this->formFactory->createBuilder(
            FormType::class,
            $entity,
            ['data_class' => $entityClass]
        );

        $fields = array_keys($classMetadata->fieldMappings);
        $translationDomain = $this->translationDomainResolver->resolve($crudOperation, $entityClass);

        foreach ($fields as $field) {
            $fieldMapping = $classMetadata->fieldMappings[$field];
            $fieldName = $fieldMapping['fieldName'];
            if (!array_key_exists('id', $fieldMapping) || false === $fieldMapping['id']) {
                $formBuilder->add(
                    $fieldName,
                    null,
                    ['translation_domain' => $translationDomain]
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
