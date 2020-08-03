<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Inflector\Inflector;
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
    public function supports(string $entityClass, string $crudOperation, Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTitle(Request $request): string
    {
        $entityClass = RequestAttributes::getEntityClass($request);
        $crudOperation = RequestAttributes::getOperation($request);
        $translationDomain = $this->translationDomainResolver->resolve($entityClass, $crudOperation, $request);
        return $this->translator->trans($crudOperation, [], $translationDomain);
//        $shortName = ClassNameUtils::getShortName($entityClass);
//        switch ($crudOperation) {
//            case CrudOperation::LIST:
//                return Inflector::pluralize($shortName);
//            default:
//                return $shortName;
//        }
    }
}
