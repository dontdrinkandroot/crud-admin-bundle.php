<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $this->crudAdminService->checkAuthorization($crudAdminRequest);
        $fieldDefinitions = $this->crudAdminService->getFieldDefinitions($crudAdminRequest);
        $entities = $this->crudAdminService->listEntities($crudAdminRequest);
        $template = $this->crudAdminService->getTemplate($crudAdminRequest);
        $title = $this->crudAdminService->getTitle($crudAdminRequest);

        return $this->crudAdminService->render(
            $template,
            [
                'title'            => $title,
                'entities'         => $entities,
                'page'             => $crudAdminRequest->getPage(),
                'perPage'          => $crudAdminRequest->getPerPage(),
                'fieldDefinitions' => $fieldDefinitions
            ]
        );
    }
}
