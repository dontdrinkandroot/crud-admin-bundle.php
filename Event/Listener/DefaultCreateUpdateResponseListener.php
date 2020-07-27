<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Symfony\Component\Routing\Router;
use Twig\Environment;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultCreateUpdateResponseListener
{
    private TemplateResolver $templateResolver;

    private TitleResolver $titleResolver;

    private RoutesResolver $routesResolver;

    private FormResolver $formResolver;

    private Environment $twig;

    private ItemResolver $itemResolver;

    private Router $router;

    private IdResolver $idResolver;

    public function __construct(
        ItemResolver $itemResolver,
        TemplateResolver $templateResolver,
        TitleResolver $titleResolver,
        RoutesResolver $routesResolver,
        FormResolver $formResolver,
        IdResolver $idResolver,
        Environment $twig,
        Router $router
    ) {
        $this->templateResolver = $templateResolver;
        $this->titleResolver = $titleResolver;
        $this->routesResolver = $routesResolver;
        $this->formResolver = $formResolver;
        $this->twig = $twig;
        $this->itemResolver = $itemResolver;
        $this->router = $router;
        $this->idResolver = $idResolver;
    }

    public function onCreateResponseEvent(CreateResponseEvent $event)
    {
        $request = $event->getRequest();
        $crudOperation = $request->get(RequestAttributes::OPERATION);
        if (!in_array($crudOperation, [CrudOperation::CREATE, CrudOperation::UPDATE], true)) {
            return;
        }

        $response = $event->getResponse();
        $routes = $this->routesResolver->resolve($request);
        $entity = $this->itemResolver->resolve($request);
        if (true === RequestAttributes::getPersistSuccess($request)) {

            $redirectUrl = null;
            if (array_key_exists(CrudOperation::READ, $routes)) {
                $redirectUrl = $this->router->generate(
                    $routes[CrudOperation::READ],
                    ['id' => $this->idResolver->resolve($entity)]
                );
            } elseif (array_key_exists(CrudOperation::LIST, $routes)) {
                $redirectUrl = $this->router->generate($routes[CrudOperation::LIST]);
            }

            if (null !== $redirectUrl) {
                $response->setStatusCode(302);
                $response->headers->set('Location', $redirectUrl);
            }

            return;
        }

        $template = $this->templateResolver->resolve($request);
        assert(null !== $template);
        $title = $this->titleResolver->resolve($request);
        $form = $this->formResolver->resolve($request);
        assert(null !== $form);

        $context = [
            'entity' => $entity,
            'title'  => $title,
            'routes' => $routes,
            'form'   => $form->createView()
        ];

        $content = $this->twig->render($template, $context);

        $response->setContent($content);
    }
}

