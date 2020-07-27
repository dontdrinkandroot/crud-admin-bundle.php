<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;


/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ProviderServiceInterface
{
    public function addProvider(ProviderInterface $provider): void;
}
