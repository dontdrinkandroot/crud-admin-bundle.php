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
class ReadAction
{
    private CrudAdminService $crudAdminService;

    public function __construct(CrudAdminService $crudAdminService)
    {
        $this->crudAdminService = $crudAdminService;
    }

    public function __invoke(Request $request): Response
    {
        $crudAdminRequest = new CrudAdminRequest(CrudOperation::READ, $request);
        $entity = $this->crudAdminService->getEntity($crudAdminRequest);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }
        if (!$this->crudAdminService->checkAuthorization($crudAdminRequest)) {
            throw new AccessDeniedException();
        }
        $template = $this->crudAdminService->getTemplate($crudAdminRequest);
        $title = $this->crudAdminService->getTitle($crudAdminRequest);
        $routes = $this->crudAdminService->getRoutes($crudAdminRequest);
        $fieldDefinitions = $this->crudAdminService->getFieldDefinitions($crudAdminRequest);

        return $this->crudAdminService->render(
            $template,
            [
                'title'            => $title,
                'entity'           => $entity,
                'routes'           => $routes,
                'fieldDefinitions' => $fieldDefinitions
            ]
        );
    }
}
