<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\Common\ClassNameUtils;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Override;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultTitleProvider implements TitleProviderInterface
{
    final public const TYPE_AUTO = 'auto';
    final public const TYPE_MANUAL = 'manual';

    public function __construct(
        private readonly TranslationDomainResolverInterface $translationDomainResolver,
        private readonly TranslatorInterface $translator,
        public string $type = self::TYPE_AUTO
    ) {
    }

    #[Override]
    public function provideTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): string
    {
        if ($this->type === self::TYPE_MANUAL) {
            return $this->translator->trans(
                id: 'title.' . strtolower($crudOperation->value),
                domain: $this->translationDomainResolver->resolveTranslationDomain($entityClass)
            );
        }

        $inflector = new EnglishInflector();
        return match ($crudOperation) {
            CrudOperation::LIST => $this->translator->trans(
                'title.list',
                ['%name%' => $inflector->pluralize(ClassNameUtils::getShortName($entityClass))[0]],
                'DdrCrudAdmin'
            ),
            CrudOperation::CREATE => $this->translator->trans(
                'title.create',
                ['%name%' => ClassNameUtils::getShortName($entityClass)],
                'DdrCrudAdmin'
            ),
            CrudOperation::READ => $this->translator->trans(
                'title.read',
                ['%name%' => ClassNameUtils::getShortName($entityClass)],
                'DdrCrudAdmin'
            ),
            CrudOperation::UPDATE => $this->translator->trans(
                'title.update',
                ['%name%' => ClassNameUtils::getShortName($entityClass)],
                'DdrCrudAdmin'
            ),
            CrudOperation::DELETE => $this->translator->trans(
                'title.delete',
                ['%name%' => ClassNameUtils::getShortName($entityClass)],
                'DdrCrudAdmin'
            ),
        };
    }
}
