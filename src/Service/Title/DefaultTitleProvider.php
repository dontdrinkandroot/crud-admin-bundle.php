<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultTitleProvider implements TitleProviderInterface
{
    public function __construct(
        private readonly TranslationDomainResolverInterface $translationDomainResolver,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(CrudOperation $crudOperation, string $entityClass, ?object $entity): string
    {
        return $this->translator->trans(
            id: $crudOperation->value,
            domain: $this->translationDomainResolver->resolveTranslationDomain($entityClass)
        );
    }
}
