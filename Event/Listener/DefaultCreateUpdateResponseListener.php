<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Event\CreateResponseEvent;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplatesResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Url\UrlResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Router;
use Twig\Environment;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultCreateUpdateResponseListener
{
    private TemplatesResolver $templateResolver;

    private TitleResolver $titleResolver;

    private RoutesResolver $routesResolver;

    private FormResolver $formResolver;

    private Environment $twig;

    private ItemResolver $itemResolver;

    private Router $router;

    private IdResolver $idResolver;

    private UrlResolver $urlResolver;

    public function __construct(
        ItemResolver $itemResolver,
        TemplatesResolver $templateResolver,
        TitleResolver $titleResolver,
        RoutesResolver $routesResolver,
        FormResolver $formResolver,
        Environment $twig,
        UrlResolver $urlResolver
    ) {
        $this->templateResolver = $templateResolver;
        $this->titleResolver = $titleResolver;
        $this->routesResolver = $routesResolver;
        $this->formResolver = $formResolver;
        $this->twig = $twig;
        $this->itemResolver = $itemResolver;
        $this->urlResolver = $urlResolver;
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

            $redirectUrl = $this->urlResolver->resolve($entity, CrudOperation::READ, $request);
            if (null === $redirectUrl) {
                $redirectUrl = $this->urlResolver->resolve(
                    RequestAttributes::getEntityClass($request),
                    CrudOperation::LIST,
                    $request
                );
            }

            if (null !== $redirectUrl) {
                $response->setStatusCode(302);
                $response->headers->set('Location', $redirectUrl);
            }

            return;
        }

        $templates = $this->templateResolver->resolve($request);
        assert(null !== $templates);
        assert(isset($templates[$crudOperation]));
        $title = $this->titleResolver->resolve($request);
        $form = $this->formResolver->resolve($request);
        assert(null !== $form);

        $context = [
            'entity' => $entity,
            'title'  => $title,
            'routes' => $routes,
            'form'   => $form->createView()
        ];

        $content = $this->twig->render($templates[$crudOperation], $context);

        $response->setContent($content);
    }
}

