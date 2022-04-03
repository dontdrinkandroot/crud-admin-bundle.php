<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TemplatesProviderInterface extends ProviderInterface
{
    public function supportsTemplates(CrudAdminContext $context): bool;

    public function provideTemplates(CrudAdminContext $context): ?array;
}
