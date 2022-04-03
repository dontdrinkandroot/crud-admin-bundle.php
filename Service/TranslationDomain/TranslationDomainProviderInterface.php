<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TranslationDomainProviderInterface extends ProviderInterface
{
    public function supportsTranslationDomain(CrudAdminContext $context): bool;

    public function resolveTranslationDomain(CrudAdminContext $context): ?string;
}
