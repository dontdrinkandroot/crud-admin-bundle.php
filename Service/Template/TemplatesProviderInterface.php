<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface TemplatesProviderInterface extends ProviderInterface
{
    public function supportsTemplates(CrudAdminContext $context): bool;

    public function provideTemplates(CrudAdminContext $context): ?array;
}
