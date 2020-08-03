<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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


    public function __construct(
        FormFactoryInterface $formFactory,
        ItemResolver $itemResolver
    ) {
        $this->formFactory = $formFactory;
        $this->itemResolver = $itemResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminContext $context): bool
    {
        $entityClass = $context->getEntityClass();
        if ($entityClass !== RequestAttributes::getEntityClass($context->getRequest())) {
            return false;
        }

        return RequestAttributes::entityClassMatches($context)
            && $context->getRequest()->attributes->has(RequestAttributes::FORM_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function provideForm(CrudAdminContext $context): ?FormInterface
    {
        $entity = $this->itemResolver->resolve($context);

        return $this->formFactory->create(RequestAttributes::getFormType($context->getRequest()), $entity)
            ->add(
                'submit',
                SubmitType::class,
                ['translation_domain' => 'DdrCrudAdmin']
            );
    }
}
