<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface FormProviderInterface extends ProviderInterface
{
    public function supportsForm(CrudAdminContext $context): bool;

    public function provideForm(CrudAdminContext $context): ?FormInterface;
}
