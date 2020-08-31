<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface TranslationDomainProviderInterface extends ProviderInterface
{
    public function supportsTranslationDomain(CrudAdminContext $context): bool;

    public function resolveTranslationDomain(CrudAdminContext $context): ?string;
}
