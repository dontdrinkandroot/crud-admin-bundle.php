<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class RequestAttributeFormProvider implements FormProviderInterface
{
    private FormFactoryInterface $formFactory;

    private ItemResolver $itemResolver;

    public function __construct(FormFactoryInterface $formFactory, ItemResolver $itemResolver)
    {
        $this->formFactory = $formFactory;
        $this->itemResolver = $itemResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRequest(Request $request): bool
    {
        return $request->attributes->has(RequestAttributes::FORM_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(Request $request): ?FormInterface
    {
        $entity = $this->itemResolver->resolve($request);

        return $this->formFactory->create(RequestAttributes::getFormType($request), $entity);
    }
}
