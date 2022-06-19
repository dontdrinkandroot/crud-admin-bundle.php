<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\CrudAdminBundle\Exception\EndProviderChainException;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface UrlProviderInterface extends ProviderInterface
{
    public function supportsUrl(CrudAdminContext $context): bool;

    /**
     * @param CrudAdminContext $context
     *
     * @return string|null
     * @throws EndProviderChainException
     */
    public function provideUrl(CrudAdminContext $context): ?string;
}
