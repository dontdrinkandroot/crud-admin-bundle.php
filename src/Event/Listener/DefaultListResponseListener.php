<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Twig\Environment;

class DefaultListResponseListener
{

    public function __construct(
        private TitleResolver $titleResolver,
        private PaginationResolver $paginationResolver,
        private FieldDefinitionsResolver $fieldDefinitionsResolver,
        private RoutesResolver $routesResolver,
        private TemplateResolver $templateResolver,
        private Environment $twig,
        private TranslationDomainResolver $translationDomainResolver
    ) {
    }

    public function onCreateResponseEvent(CreateResponseEvent $event): void
    {
        $context = $event->context;
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
            'translationDomain' => $this->translationDomainResolver->resolve($context)
        ];

        $content = $this->twig->render($templates[$crudOperation], $context);

        $event->response->setContent($content);
    }
}
