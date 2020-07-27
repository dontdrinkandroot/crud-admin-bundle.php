<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttribute;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Twig\Environment;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultUpdateResponseListener
{
    private TemplateResolver $templateResolver;

    private TitleResolver $titleResolver;

    private RoutesResolver $routesResolver;

    private FormResolver $formResolver;

    private Environment $twig;

    private ItemResolver $itemResolver;

    public function __construct(
        ItemResolver $itemResolver,
        TemplateResolver $templateResolver,
        TitleResolver $titleResolver,
        RoutesResolver $routesResolver,
        FormResolver $formResolver,
        Environment $twig
    ) {
        $this->templateResolver = $templateResolver;
        $this->titleResolver = $titleResolver;
        $this->routesResolver = $routesResolver;
        $this->formResolver = $formResolver;
        $this->twig = $twig;
        $this->itemResolver = $itemResolver;
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $request = $event->getRequest();
        if (CrudOperation::UPDATE !== $request->get(RequestAttribute::OPERATION)) {
            return;
        }

        $entity = $this->itemResolver->resolve($request);
        $template = $this->templateResolver->resolve($request);
        $title = $this->titleResolver->resolve($request);
        $routes = $this->routesResolver->resolve($request);
        $form = $this->formResolver->resolve($request);

        $context = [
            'entity' => $entity,
            'title'  => $title,
            'routes' => $routes,
            'form'   => $form->createView()
        ];

        $content = $this->twig->render($template, $context);

        $event->getResponse()->setContent($content);
    }
}

