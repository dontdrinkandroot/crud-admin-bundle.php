<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\LegacyOperationProviderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface FormProviderInterface extends CrudAdminProviderInterface
{
    public function provideForm(CrudAdminContext $context): ?FormInterface;
}
