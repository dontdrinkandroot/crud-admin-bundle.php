<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultTitleProvider implements TitleProviderInterface
{
    private TranslatorInterface $translator;

    private TranslationDomainResolver $translationDomainResolver;

    public function __construct(TranslatorInterface $translator, TranslationDomainResolver $translationDomainResolver)
    {
        $this->translator = $translator;
        $this->translationDomainResolver = $translationDomainResolver;
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
