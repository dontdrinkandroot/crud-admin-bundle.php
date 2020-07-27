<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormProviderInterface;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

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
    public function supports(Request $request): bool
    {
        $crudAdminRequest = new CrudAdminRequest($request);
        return null !== $this->managerRegistry->getManagerForClass($crudAdminRequest->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(Request $request): ?FormInterface
    {
        $crudAdminRequest = new CrudAdminRequest($request);
        $entityClass = $crudAdminRequest->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $classMetadata = $entityManager->getClassMetadata($entityClass);
        $entity = $crudAdminRequest->getData();
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
