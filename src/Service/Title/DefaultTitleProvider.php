<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultTitleProvider implements TitleProviderInterface
{
    public function __construct(
        private readonly TranslationDomainResolver $translationDomainResolver,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTitle(string $crudOperation, string $entityClass, ?object $entity): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(string $crudOperation, string $entityClass, ?object $entity): string
    {
        return $this->translator->trans(
            id: $crudOperation,
            domain: $this->translationDomainResolver->resolve($crudOperation, $entityClass)
        );
    }
}
