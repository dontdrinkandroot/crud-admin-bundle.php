<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\CrudAdminBundle\Exception\EndProviderChainException;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;

interface UrlProviderInterface extends CrudAdminProviderInterface
{
    /**
     * @param CrudAdminContext $context
     *
     * @return string|null
     * @throws EndProviderChainException
     */
    public function provideUrl(CrudAdminContext $context): ?string;
}
