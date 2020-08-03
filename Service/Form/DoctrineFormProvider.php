<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineFormProvider implements FormProviderInterface
{
    private ManagerRegistry $managerRegistry;

    private FormFactoryInterface $formFactory;

    private ItemResolver $itemResolver;

    private TranslationDomainResolver $translationDomainResolver;

    private TranslatorInterface $translator;

    public function __construct(
        ManagerRegistry $managerRegistry,
        FormFactoryInterface $formFactory,
        ItemResolver $itemResolver,
        TranslationDomainResolver $translationDomainResolver,
        TranslatorInterface $translator
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->formFactory = $formFactory;
        $this->itemResolver = $itemResolver;
        $this->translationDomainResolver = $translationDomainResolver;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminContext $context): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($context->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(CrudAdminContext $context): ?FormInterface
    {
        $entityClass = $context->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $classMetadata = $entityManager->getClassMetadata($entityClass);
        $entity = $this->itemResolver->resolve($context);
        $formBuilder = $this->formFactory->createBuilder(FormType::class, $entity);

        $fields = $this->getFields($context);
        if (null === $fields) {
            $fields = array_keys($classMetadata->fieldMappings);
        }

        $translationDomain = $this->translationDomainResolver->resolve($context);

        foreach ($fields as $field) {
            $fieldMapping = $classMetadata->fieldMappings[$field];
            $fieldName = $fieldMapping['fieldName'];
            if (!array_key_exists('id', $fieldMapping) || false === $fieldMapping['id']) {
                $formBuilder->add(
                    $fieldName,
                    null,
                    [
                        'label' => $this->translator->trans(ucfirst($fieldName), [], $translationDomain)
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

    private function getFields(CrudAdminContext $context): ?array
    {
        $operation = $context->getCrudOperation();
        if (
            !RequestAttributes::entityClassMatches($context)
            || null === $fields = RequestAttributes::getFields($context->getRequest())
        ) {
            return null;
        }

        if (array_key_exists($operation, $fields)) {
            return $fields[$operation];
        }

        return null;
    }
}
