<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Title;

use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface TitleProviderInterface extends RequestProviderInterface
{
    public function provideTitle(Request $request): ?string;
}
