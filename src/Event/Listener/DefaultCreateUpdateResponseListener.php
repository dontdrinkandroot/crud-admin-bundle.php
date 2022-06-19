<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Common\Asserted;
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

class DefaultCreateUpdateResponseListener
{
    public function __construct(
        private ItemResolver $itemResolver,
        private TemplatesResolver $templateResolver,
        private TitleResolver $titleResolver,
        private RoutesResolver $routesResolver,
        private FormResolver $formResolver,
        private Environment $twig,
        private UrlResolver $urlResolver,
        private TranslationDomainResolver $translationDomainResolver
    ) {
    }

    public function onCreateResponseEvent(CreateResponseEvent $event): void
    {
        $context = $event->context;
        $crudOperation = $context->getCrudOperation();
        if (!in_array($crudOperation, [CrudOperation::CREATE, CrudOperation::UPDATE], true)) {
            return;
        }

        $response = $event->response;
        $routes = $this->routesResolver->resolve($context);
        $entity = CrudOperation::UPDATE === $context->getCrudOperation()
            ? $this->itemResolver->resolve($context)
            : null;
        if ($context->isItemPersisted()) {
            $redirectContext = $context
                ->withOperation(CrudOperation::READ)
                ->setEntity($context->getEntity());
            $redirectUrl = $this->urlResolver->resolve($redirectContext);
            if (null === $redirectUrl) {
                $redirectContext = $context->withOperation(CrudOperation::LIST);
                $redirectUrl = $this->urlResolver->resolve($redirectContext);
            }

            if (null !== $redirectUrl) {
                $response->setStatusCode(302);
                $response->headers->set('Location', $redirectUrl);
            }

            return;
        }

        $templates = Asserted::notNull($this->templateResolver->resolve($context));
        assert(isset($templates[$crudOperation]));
        $title = $this->titleResolver->resolve($context);
        $form = Asserted::notNull($this->formResolver->resolve($context));

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
