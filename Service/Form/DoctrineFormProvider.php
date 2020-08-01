<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    private ItemResolver $itemResolver;

    public function __construct(
        ManagerRegistry $managerRegistry,
        FormFactoryInterface $formFactory,
        ItemResolver $itemResolver
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->formFactory = $formFactory;
        $this->itemResolver = $itemResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $entityClass, string $crudOperation, Request $request): bool
    {
        return null !== $this->managerRegistry->getManagerForClass(RequestAttributes::getEntityClass($request));
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(Request $request): ?FormInterface
    {
        $entityClass = RequestAttributes::getEntityClass($request);
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $classMetadata = $entityManager->getClassMetadata($entityClass);
        $entity = $this->itemResolver->resolve($request);
        $formBuilder = $this->formFactory->createBuilder(FormType::class, $entity);
        $shortName = ClassNameUtils::getShortName($entityClass);

        foreach ($classMetadata->fieldMappings as $fieldMapping) {
            $fieldName = $fieldMapping['fieldName'];
            if (!array_key_exists('id', $fieldMapping) || false === $fieldMapping['id']) {
                $formBuilder->add(
                    $fieldName,
                    null,
                    [
                        'label' => $shortName . '.' . $fieldName
//                    , 'translation_domain' => $this->getTranslationDomain($attributes)
                    ]
                );
            }
        }

        $formBuilder->add('submit', SubmitType::class);

        return $formBuilder->getForm();
    }
}
