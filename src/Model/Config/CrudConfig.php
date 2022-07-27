<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model\Config;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Config\KnpPaginator\TemplateConfig;

class CrudConfig
{
    #[SerializedName('form_type')]
    private ?string $formType = null;

    #[SerializedName('templates')]
    private ?TemplatesConfig $templatesConfig;

    #[SerializedName('route')]
    private ?RouteConfig $routeConfig;

    #[SerializedName('field_definitions')]
    private ?FieldDefinitionsConfig $fieldDefinitionsConfig;

    /**
     * @param class-string $resourceClass
     */
    public function __construct(public readonly string $resourceClass)
    {
        $this->templatesConfig = null;
        $this->routeConfig = null;
        $this->fieldDefinitionsConfig = null;
    }

    public function getFormType(): ?string
    {
        return $this->formType;
    }

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
}
