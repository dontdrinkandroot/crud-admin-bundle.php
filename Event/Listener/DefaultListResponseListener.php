<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttribute;
use Dontdrinkandroot\CrudAdminBundle\Service\Collection\CollectionResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Twig\Environment;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultListResponseListener
{
    private TitleResolver $titleResolver;

    private CollectionResolver $collectionResolver;

    private FieldDefinitionsResolver $fieldDefinitionsResolver;

    private RoutesResolver $routesResolver;

    private TemplateResolver $templateResolver;

    private Environment $twig;

    public function __construct(
        TitleResolver $titleResolver,
        CollectionResolver $collectionResolver,
        FieldDefinitionsResolver $fieldDefinitionsResolver,
        RoutesResolver $routesResolver,
        TemplateResolver $templateResolver,
        Environment $twig
    ) {
        $this->titleResolver = $titleResolver;
        $this->collectionResolver = $collectionResolver;
        $this->fieldDefinitionsResolver = $fieldDefinitionsResolver;
        $this->routesResolver = $routesResolver;
        $this->templateResolver = $templateResolver;
        $this->twig = $twig;
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $request = $event->getRequest();
        if (CrudOperation::LIST !== $request->get(RequestAttribute::OPERATION)) {
            return;
        }

        $crudAdminRequest = new CrudAdminRequest($request);

        $template = $this->templateResolver->resolve($request);
        $context = [
            'title'            => $this->titleResolver->resolve($request),
            'entities'         => $this->collectionResolver->resolve($request),
            'page'             => $crudAdminRequest->getPage(),
            'perPage'          => $crudAdminRequest->getPerPage(),
            'fieldDefinitions' => $this->fieldDefinitionsResolver->resolve($request),
            'routes'           => $this->routesResolver->resolve($request)
        ];

        $content = $this->twig->render($template, $context);

        $event->getResponse()->setContent($content);
    }
}
