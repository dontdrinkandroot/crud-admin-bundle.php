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
class ListAction
{
    private CrudAdminService $crudAdminService;

    public function __construct(CrudAdminService $crudAdminService)
    {
        $this->crudAdminService = $crudAdminService;
    }

    public function __invoke(Request $request): Response
    {
        $crudAdminRequest = new CrudAdminRequest(CrudOperation::LIST, $request);
        if (!$this->crudAdminService->checkAuthorization($crudAdminRequest)) {
            throw new AccessDeniedException();
        }
        $fieldDefinitions = $this->crudAdminService->getFieldDefinitions($crudAdminRequest);
        $entities = $this->crudAdminService->listEntities($crudAdminRequest);
        $template = $this->crudAdminService->getTemplate($crudAdminRequest);
        $title = $this->crudAdminService->getTitle($crudAdminRequest);
        $routes = $this->crudAdminService->getRoutes($crudAdminRequest);

        $context = [
            'title'            => $title,
            'entities'         => $entities,
            'page'             => $crudAdminRequest->getPage(),
            'perPage'          => $crudAdminRequest->getPerPage(),
            'fieldDefinitions' => $fieldDefinitions,
            'routes'           => $routes
        ];

        return $this->crudAdminService->render($template, $context);
    }
}
