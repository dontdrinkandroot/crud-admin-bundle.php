<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttribute;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Twig\Environment;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultReadResponseListener
{
    private ItemResolver $itemResolver;

    private TitleResolver $titleResolver;

    private RoutesResolver $routesResolver;

    private FieldDefinitionsResolver $fieldDefinitionsResolver;

    private Environment $twig;

    private TemplateResolver $templateResolver;

    public function __construct(
        ItemResolver $itemResolver,
        TitleResolver $titleResolver,
        RoutesResolver $routesResolver,
        FieldDefinitionsResolver $fieldDefinitionsResolver,
        TemplateResolver $templateResolver,
        Environment $twig
    ) {
        $this->itemResolver = $itemResolver;
        $this->titleResolver = $titleResolver;
        $this->routesResolver = $routesResolver;
        $this->fieldDefinitionsResolver = $fieldDefinitionsResolver;
        $this->twig = $twig;
        $this->templateResolver = $templateResolver;
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $request = $event->getRequest();
        if (CrudOperation::READ !== $request->get(RequestAttribute::OPERATION)) {
            return;
        }

        $entity = $this->itemResolver->resolve($request);
        $template = $this->templateResolver->resolve($request);
        $title = $this->titleResolver->resolve($request);
        $routes = $this->routesResolver->resolve($request);
        $fieldDefinitions = $this->fieldDefinitionsResolver->resolve($request);

        $context =  [
            'title'            => $title,
            'entity'           => $entity,
            'routes'           => $routes,
            'fieldDefinitions' => $fieldDefinitions
        ];

        $content = $this->twig->render($template, $context);

        $event->getResponse()->setContent($content);
    }
}
