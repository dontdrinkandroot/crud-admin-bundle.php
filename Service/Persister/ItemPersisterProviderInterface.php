<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ItemPersisterProviderInterface extends ProviderInterface
{
    public function persist(Request $request);
}
