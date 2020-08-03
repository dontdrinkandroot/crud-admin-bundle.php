<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineFieldDefinitionsProvider implements FieldDefinitionsProviderInterface
{
    private ManagerRegistry $managerRegistry;

    private TranslatorInterface $translator;

    private TranslationDomainResolver $translationDomainResolver;

    public function __construct(ManagerRegistry $managerRegistry, TranslatorInterface $translator, TranslationDomainResolver $translationDomainResolver)
    {
        $this->managerRegistry = $managerRegistry;
        $this->translator = $translator;
        $this->translationDomainResolver = $translationDomainResolver;
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
    public function provideFieldDefinitions(CrudAdminContext $context): ?array
    {
        $entityClass = $context->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $classMetadata = $entityManager->getClassMetadata($entityClass);

        $fields = $this->getFields($context);
        if (null === $fields) {
            $fields = array_keys($classMetadata->fieldMappings);
        }

        $translationDomain = $this->translationDomainResolver->resolve($context);

        $fieldDefinitions = [];
        foreach ($fields as $field) {
            $fieldMapping = $classMetadata->fieldMappings[$field];
            $type = $fieldMapping['type'];
            $fieldName = $fieldMapping['fieldName'];
            $filterable = false;
            if (in_array($type, ['string', 'integer'])) {
                $filterable = true;
            }

            $fieldDefinitions[] = new FieldDefinition(
                $fieldName,
                $this->translator->trans(ucfirst($fieldName), [], $translationDomain),
                $type,
                true,
                $filterable
            );
        }

        return $fieldDefinitions;
    }

    private function getFields(CrudAdminContext $context): ?array
    {
        $operation = $context->getCrudOperation();
        if (!RequestAttributes::entityClassMatches($context)) {
            return null;
        }

        $fields = RequestAttributes::getFields($context->getRequest());
        if (null === $fields) {
            return null;
        }

        if (array_key_exists($operation, $fields)) {
            return $fields[$operation];
        }

        return null;
    }
}
