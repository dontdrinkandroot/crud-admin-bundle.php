<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TitleProvider;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface TitleProviderInterface extends ProviderInterface
{
    public function provideTitle(CrudAdminRequest $request): ?string;
}
