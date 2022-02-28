<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CrudAdminContext
{
    private Request $request;

    private string $crudOperation;

    private bool $entityResolved = false;

    private ?object $entity = null;

    private bool $paginationResolved = false;

    private ?PaginationInterface $pagination = null;

    /** @var class-string $entityClass */
    private string $entityClass;

    private bool $translationDomainResolved = false;

    private ?string $translationDomain = null;

    private bool $formResolved = false;

    private ?FormInterface $form = null;

    private bool $routesResolved = false;

    private ?array $routes = null;

    private bool $itemPersisted = false;

    private bool $templatesResolved = false;

    private ?array $templates = null;

    private ?string $title = null;

    private bool $titleResolved = false;

    private bool $fieldDefinitionsResolved = false;

    private ?array $fieldDefinitions = null;

    /**
     * @param class-string $entityClass
     * @param string       $crudOperation
     * @param Request      $request
     */
    public function __construct(string $entityClass, string $crudOperation, Request $request)
    {
        $this->request = $request;
        $this->entityClass = $entityClass;
        $this->crudOperation = $crudOperation;
    }

    public function setCrudOperation(string $crudOperation): void
    {
        $this->crudOperation = $crudOperation;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function isEntityResolved(): bool
    {
        return $this->entityResolved;
    }

    public function setEntityResolved(bool $entityResolved = true): void
    {
        $this->entityResolved = $entityResolved;
    }

    public function setEntity(?object $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    public function getEntity(): ?object
    {
        return $this->entity;
    }

    public function getCrudOperation(): string
    {
        return $this->crudOperation;
    }

    /** @return class-string */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function isTranslationDomainResolved(): bool
    {
        return $this->translationDomainResolved;
    }

    public function setTranslationDomainResolved(bool $translationDomainResolved = true): void
    {
        $this->translationDomainResolved = $translationDomainResolved;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(?string $translationDomain): void
    {
        $this->translationDomain = $translationDomain;
    }

    public function isFormResolved(): bool
    {
        return $this->formResolved;
    }

    public function setFormResolved(bool $formResolved = true): void
    {
        $this->formResolved = $formResolved;
    }

    public function getForm(): ?FormInterface
    {
        return $this->form;
    }

    public function setForm(?FormInterface $form): void
    {
        $this->form = $form;
    }

    public function isRoutesResolved(): bool
    {
        return $this->routesResolved;
    }

    public function setRoutesResolved(bool $routesResolved = true): void
    {
        $this->routesResolved = $routesResolved;
    }

    public function getRoutes(): ?array
    {
        return $this->routes;
    }

    public function setRoutes(?array $routes): void
    {
        $this->routes = $routes;
    }

    public function isItemPersisted(): bool
    {
        return $this->itemPersisted;
    }

    public function setItemPersisted(bool $itemPersisted = true): void
    {
        $this->itemPersisted = $itemPersisted;
    }

    public function getTemplates(): ?array
    {
        return $this->templates;
    }

    public function setTemplates(?array $templates): void
    {
        $this->templates = $templates;
    }

    public function isTemplatesResolved(): bool
    {
        return $this->templatesResolved;
    }

    public function setTemplatesResolved(bool $templatesResolved = true): void
    {
        $this->templatesResolved = $templatesResolved;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function isTitleResolved(): bool
    {
        return $this->titleResolved;
    }

    public function setTitleResolved(bool $titleResolved = true): void
    {
        $this->titleResolved = $titleResolved;
    }

    public function withOperation(string $crudOperation): CrudAdminContext
    {
        return new CrudAdminContext($this->getEntityClass(), $crudOperation, $this->getRequest());
    }

    public function getFieldDefinitions(): ?array
    {
        return $this->fieldDefinitions;
    }

    public function setFieldDefinitions(?array $fieldDefinitions): void
    {
        $this->fieldDefinitions = $fieldDefinitions;
    }

    public function isFieldDefinitionsResolved(): bool
    {
        return $this->fieldDefinitionsResolved;
    }

    public function setFieldDefinitionsResolved(bool $fieldDefinitionsResolved = true): void
    {
        $this->fieldDefinitionsResolved = $fieldDefinitionsResolved;
    }

    public function getPagination(): ?PaginationInterface
    {
        return $this->pagination;
    }

    public function setPagination(?PaginationInterface $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function isPaginationResolved(): bool
    {
        return $this->paginationResolved;
    }

    public function setPaginationResolved(bool $paginationResolved = true): void
    {
        $this->paginationResolved = $paginationResolved;
    }
}
