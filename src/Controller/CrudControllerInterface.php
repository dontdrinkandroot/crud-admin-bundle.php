<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @template T of object
 */
interface CrudControllerInterface
{
    /**
     * @return class-string<T>
     */
    public function getEntityClass(): string;

    public function listAction(Request $request): Response;

    public function createAction(Request $request): Response;

    public function readAction(Request $request, mixed $id): Response;

    public function updateAction(Request $request, mixed $id): Response;

    public function deleteAction(Request $request, mixed $id): Response;
}
