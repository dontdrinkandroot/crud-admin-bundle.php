<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplatesResolver;
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

    private TemplatesResolver $templateResolver;

    public function __construct(
        ItemResolver $itemResolver,
        TitleResolver $titleResolver,
        RoutesResolver $routesResolver,
        FieldDefinitionsResolver $fieldDefinitionsResolver,
        TemplatesResolver $templateResolver,
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
        $context = $event->getContext();
        $request = $event->getRequest();
        $crudOperation = $context->getCrudOperation();
        if (CrudOperation::READ !== $crudOperation) {
            return;
        }

        $entity = $this->itemResolver->resolve($context);
        $templates = $this->templateResolver->resolve($context);
        assert(null !== $templates);
        assert(isset($templates[$crudOperation]));
        $title = $this->titleResolver->resolve($context);
        $routes = $this->routesResolver->resolve($context);
        $fieldDefinitions = $this->fieldDefinitionsResolver->resolve($context);

        $context =  [
            'title'            => $title,
            'entity'           => $entity,
            'routes'           => $routes,
            'fieldDefinitions' => $fieldDefinitions,
        ];

        $content = $this->twig->render($templates[$crudOperation], $context);

        $event->getResponse()->setContent($content);
    }
}
