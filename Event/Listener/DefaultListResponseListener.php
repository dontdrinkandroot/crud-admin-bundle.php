<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionsResolver;
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
        $request = $event->getRequest();
        $crudOperation = RequestAttributes::getOperation($request);
        if (CrudOperation::LIST !== $crudOperation) {
            return;
        }

        $templates = $this->templateResolver->resolve($request);
        assert(null !== $templates);
        assert(isset($templates[$crudOperation]));
        $context = [
            'title'            => $this->titleResolver->resolve($request),
            'entities'         => $this->paginationResolver->resolve($request),
            'fieldDefinitions' => $this->fieldDefinitionsResolver->resolve($request),
            'routes'           => $this->routesResolver->resolve($request),
        ];

        $content = $this->twig->render($templates[$crudOperation], $context);

        $event->getResponse()->setContent($content);
    }
}
