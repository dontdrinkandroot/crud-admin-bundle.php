<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CreateAction
{
    private CrudAdminService $crudAdminService;

    public function __construct(CrudAdminService $crudAdminService)
    {
        $this->crudAdminService = $crudAdminService;
    }

    public function __invoke(Request $request): Response
    {
        $crudAdminRequest = new CrudAdminRequest(CrudOperation::CREATE, $request);
        if (!$this->crudAdminService->checkAuthorization($crudAdminRequest)) {
            throw new AccessDeniedException();
        }
        $entity = $this->crudAdminService->createNewInstance($crudAdminRequest);
        $template = $this->crudAdminService->getTemplate($crudAdminRequest);
        $title = $this->crudAdminService->getTitle($crudAdminRequest);
        $routes = $this->crudAdminService->getRoutes($crudAdminRequest);
        $form = $this->crudAdminService->getForm($crudAdminRequest);

        $context = [
            'entity' => $entity,
            'title'  => $title,
            'routes' => $routes,
            'form'   => $form->createView()
        ];

        return $this->crudAdminService->render($template, $context);
    }
}
