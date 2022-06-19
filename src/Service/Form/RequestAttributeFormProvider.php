<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Form;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class RequestAttributeFormProvider implements FormProviderInterface
{
    public function __construct(private FormFactoryInterface $formFactory, private ItemResolver $itemResolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsForm(CrudAdminContext $context): bool
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
        $entity = CrudOperation::UPDATE === $context->getCrudOperation()
            ? $this->itemResolver->resolve($context)
            : $context->getEntity();

        return $this->formFactory->create(
            Asserted::notNull(RequestAttributes::getFormType($context->getRequest())),
            $entity
        )
            ->add(
                'submit',
                SubmitType::class,
                ['translation_domain' => 'DdrCrudAdmin']
            );
    }
}
