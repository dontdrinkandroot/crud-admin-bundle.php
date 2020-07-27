<?php

namespace Dontdrinkandroot\CrudAdminBundle\Action;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DeleteAction
{
    private CrudAdminService $crudAdminService;

    public function __construct(CrudAdminService $crudAdminService)
    {
        $this->crudAdminService = $crudAdminService;
    }

    public function __invoke(Request $request): Response
    {
        $crudAdminRequest = new CrudAdminRequest($request, CrudOperation::DELETE);
        $entity = $this->crudAdminService->getEntity($crudAdminRequest);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$this->crudAdminService->checkAuthorization($crudAdminRequest)) {
            throw new AccessDeniedException();
        }

        return $this->crudAdminService->createResponse($crudAdminRequest);
    }
}
