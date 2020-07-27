<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface TemplateProviderInterface extends RequestProviderInterface
{
    public function provideTemplate(Request $request): ?string;
}
