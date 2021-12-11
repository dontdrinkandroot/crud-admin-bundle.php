<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplatesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Twig\Environment;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultCreateUpdateResponseListener
{
    private TemplatesResolver $templateResolver;

    private TitleResolver $titleResolver;

    private RoutesResolver $routesResolver;

    private FormResolver $formResolver;

    private Environment $twig;

    private ItemResolver $itemResolver;

    private UrlResolver $urlResolver;

    private TranslationDomainResolver $translationDomainResolver;

    public function __construct(
        ItemResolver $itemResolver,
        TemplatesResolver $templateResolver,
        TitleResolver $titleResolver,
        RoutesResolver $routesResolver,
        FormResolver $formResolver,
        Environment $twig,
        UrlResolver $urlResolver,
        TranslationDomainResolver $translationDomainResolver
    ) {
        $this->templateResolver = $templateResolver;
        $this->titleResolver = $titleResolver;
        $this->routesResolver = $routesResolver;
        $this->formResolver = $formResolver;
        $this->twig = $twig;
        $this->itemResolver = $itemResolver;
        $this->urlResolver = $urlResolver;
        $this->translationDomainResolver = $translationDomainResolver;
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $context = $event->getContext();
        $crudOperation = $context->getCrudOperation();
        if (!in_array($crudOperation, [CrudOperation::CREATE, CrudOperation::UPDATE], true)) {
            return;
        }

        $response = $event->getResponse();
        $routes = $this->routesResolver->resolve($context);
        $entity = $this->itemResolver->resolve($context);
        if ($context->isItemPersisted()) {

            $redirectContext = $context->recreateWithOperation(CrudOperation::READ)
                ->setEntity($context->getEntity());
            $redirectUrl = $this->urlResolver->resolve($redirectContext);
            if (null === $redirectUrl) {
                $redirectContext = $context->recreateWithOperation(CrudOperation::LIST);
                $redirectUrl = $this->urlResolver->resolve($redirectContext);
            }

            if (null !== $redirectUrl) {
                $response->setStatusCode(302);
                $response->headers->set('Location', $redirectUrl);
            }

            return;
        }

        $templates = $this->templateResolver->resolve($context);
        assert(null !== $templates);
        assert(isset($templates[$crudOperation]));
        $title = $this->titleResolver->resolve($context);
        $form = $this->formResolver->resolve($context);
        assert(null !== $form);

        $context = [
            'entity'            => $entity,
            'title'             => $title,
            'routes'            => $routes,
            'form'              => $form->createView(),
            'translationDomain' => $this->translationDomainResolver->resolve($context)
        ];

        $content = $this->twig->render($templates[$crudOperation], $context);

        $response->setContent($content);
    }
}

