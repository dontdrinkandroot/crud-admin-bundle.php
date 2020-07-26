<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormProvider;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineFormProvider implements FormProviderInterface
{
    private ManagerRegistry $managerRegistry;

    private FormFactoryInterface $formFactory;

    public function __construct(ManagerRegistry $managerRegistry, FormFactoryInterface $formFactory)
    {
        $this->managerRegistry = $managerRegistry;
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminRequest $request): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($request->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(CrudAdminRequest $request): ?FormInterface
    {
        $entityClass = $request->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $classMetadata = $entityManager->getClassMetadata($entityClass);
        $entity = $request->getData();
        $formBuilder = $this->formFactory->createBuilder(FormType::class, $entity);
        $shortName = ClassNameUtils::getShortName($entityClass);
        foreach ($classMetadata->fieldMappings as $fieldMapping) {
            $formBuilder->add(
                $fieldMapping['fieldName'],
                null,
                [
                    'label' => $shortName . '.' . $fieldMapping['fieldName']
//                    , 'translation_domain' => $this->getTranslationDomain($attributes)
                ]
            );
        }
        $formBuilder->add('submit', SubmitType::class, ['label' => 'Save']);

        return $formBuilder->getForm();
    }
}
