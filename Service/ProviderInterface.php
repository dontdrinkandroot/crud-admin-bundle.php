<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ProviderInterface
{
    public function supports(Request $request): bool;
}
