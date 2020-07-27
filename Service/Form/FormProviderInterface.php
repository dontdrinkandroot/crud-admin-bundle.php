<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface FormProviderInterface extends RequestProviderInterface
{
    public function provideForm(Request $request): ?FormInterface;
}
