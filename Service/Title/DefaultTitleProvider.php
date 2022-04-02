<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultTitleProvider implements TitleProviderInterface
{
    public function __construct(private TranslatorInterface $translator, private TranslationDomainResolver $translationDomainResolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTitle(CrudAdminContext $context): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(CrudAdminContext $context): string
    {
        $crudOperation = $context->getCrudOperation();
        $translationDomain = $this->translationDomainResolver->resolve($context);

        return $this->translator->trans($crudOperation, [], $translationDomain);
    }
}
