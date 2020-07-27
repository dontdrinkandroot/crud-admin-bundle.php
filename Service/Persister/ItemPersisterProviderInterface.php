<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ItemPersisterProviderInterface extends RequestProviderInterface
{
    public function persist(Request $request);
}
