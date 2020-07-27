<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface RequestProviderInterface extends ProviderInterface
{
    public function supportsRequest(Request $request): bool;
}
