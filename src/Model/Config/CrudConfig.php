<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model\Config;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

class CrudConfig
{
    /** @psalm-var ?class-string<FormTypeInterface> */
    #[SerializedName('form_type')]
    private ?string $formType = null;

    #[SerializedName('templates')]
    private ?TemplatesConfig $templatesConfig;

    #[SerializedName('route')]
    private ?RouteConfig $routeConfig;

    #[SerializedName('field_definitions')]
    private ?FieldDefinitionsConfig $fieldDefinitionsConfig;

    #[SerializedName('default_sort')]
    private ?DefaultSortConfig $defaultSortConfig;

    /** @psalm-param class-string $entityClass */
    public function __construct(public readonly string $entityClass)
    {
        $this->templatesConfig = null;
        $this->routeConfig = null;
        $this->fieldDefinitionsConfig = null;
        $this->defaultSortConfig = null;
    }

    /** @psalm-return ?class-string<FormTypeInterface> */
    public function getFormType(): ?string
    {
        return $this->formType;
    }

    /** @psalm-param ?class-string<FormTypeInterface> $formType */
    public function setFormType(?string $formType): void
    {
        $this->formType = $formType;
    }

    public function getTemplatesConfig(): ?TemplatesConfig
    {
        return $this->templatesConfig;
    }

    public function setTemplatesConfig(?TemplatesConfig $templatesConfig): void
    {
        $this->templatesConfig = $templatesConfig;
    }

    public function getRouteConfig(): ?RouteConfig
    {
        return $this->routeConfig;
    }

    public function setRouteConfig(?RouteConfig $routeConfig): void
    {
        $this->routeConfig = $routeConfig;
    }

    public function getFieldDefinitionsConfig(): ?FieldDefinitionsConfig
    {
        return $this->fieldDefinitionsConfig;
    }

    public function setFieldDefinitionsConfig(?FieldDefinitionsConfig $fieldDefinitionsConfig): void
    {
        $this->fieldDefinitionsConfig = $fieldDefinitionsConfig;
    }

    public function getDefaultSortConfig(): ?DefaultSortConfig
    {
        return $this->defaultSortConfig;
    }

    public function setDefaultSortConfig(?DefaultSortConfig $defaultSortConfig): void
    {
        $this->defaultSortConfig = $defaultSortConfig;
    }
}
