<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface IdProviderInterface extends ProviderInterface
{
    public function supportsEntity(object $entity);

    public function provideId(object $entity);
}
