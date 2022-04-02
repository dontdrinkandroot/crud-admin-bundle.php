<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface IdProviderInterface extends ProviderInterface
{
    public function supportsId(CrudAdminContext $context): bool;

    public function provideId(CrudAdminContext $context): mixed;
}
