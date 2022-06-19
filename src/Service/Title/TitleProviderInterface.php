<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TitleProviderInterface extends ProviderInterface
{
    public function supportsTitle(CrudAdminContext $context): bool;

    public function provideTitle(CrudAdminContext $context): ?string;
}
