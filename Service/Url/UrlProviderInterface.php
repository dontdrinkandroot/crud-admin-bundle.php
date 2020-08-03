<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;

interface UrlProviderInterface extends CrudAdminProviderInterface
{
    public function provideUrl(CrudAdminContext $context): ?string;
}
