<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FormProvider;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface FormProviderInterface extends ProviderInterface
{
    public function provideForm(CrudAdminRequest $request): ?FormInterface;
}
