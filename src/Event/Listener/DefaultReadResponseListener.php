<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplatesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolver;
use Twig\Environment;

class DefaultReadResponseListener
{
    public function __construct(
        private ItemResolver $itemResolver,
        private TitleResolver $titleResolver,
        private RoutesResolver $routesResolver,
        private FieldDefinitionsResolver $fieldDefinitionsResolver,
        private TemplatesResolver $templateResolver,
        private Environment $twig,
        private TranslationDomainResolver $translationDomainResolver
    ) {
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $context = $event->context;
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

        $context = [
            'title'             => $title,
            'entity'            => $entity,
            'routes'            => $routes,
            'fieldDefinitions'  => $fieldDefinitions,
            'translationDomain' => $this->translationDomainResolver->resolve($context)
        ];

        $content = $this->twig->render($templates[$crudOperation], $context);

        $event->response->setContent($content);
    }
}
