<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplatesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Twig\Environment;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultListResponseListener
{
    private TitleResolver $titleResolver;

    private PaginationResolver $paginationResolver;

    private FieldDefinitionsResolver $fieldDefinitionsResolver;

    private RoutesResolver $routesResolver;

    private TemplatesResolver $templateResolver;

    private Environment $twig;

    public function __construct(
        TitleResolver $titleResolver,
        PaginationResolver $paginationResolver,
        FieldDefinitionsResolver $fieldDefinitionsResolver,
        RoutesResolver $routesResolver,
        TemplatesResolver $templateResolver,
        Environment $twig
    ) {
        $this->titleResolver = $titleResolver;
        $this->paginationResolver = $paginationResolver;
        $this->fieldDefinitionsResolver = $fieldDefinitionsResolver;
        $this->routesResolver = $routesResolver;
        $this->templateResolver = $templateResolver;
        $this->twig = $twig;
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $context = $event->getContext();
        $request = $event->getRequest();
        $crudOperation = $context->getCrudOperation();
        if (CrudOperation::LIST !== $crudOperation) {
            return;
        }

        $templates = $this->templateResolver->resolve($context);
        assert(null !== $templates);
        assert(isset($templates[$crudOperation]));
        $context = [
            'title'            => $this->titleResolver->resolve($context),
            'entities'         => $this->paginationResolver->resolve($context),
            'fieldDefinitions' => $this->fieldDefinitionsResolver->resolve($context),
            'routes'           => $this->routesResolver->resolve($context),
        ];

        $content = $this->twig->render($templates[$crudOperation], $context);

        $event->getResponse()->setContent($content);
    }
}
