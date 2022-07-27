<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model\Config;

use Symfony\Component\Serializer\Annotation\SerializedName;

class RouteConfig
{
    #[SerializedName('name_prefix')]
    private ?string $namePrefix = null;

    #[SerializedName('path_prefix')]
    private ?string $pathPrefix = null;

    public function getNamePrefix(): ?string
    {
        return $this->namePrefix;
    }

    public function setNamePrefix(?string $namePrefix): void
    {
        $this->namePrefix = $namePrefix;
    }

    public function getPathPrefix(): ?string
    {
        return $this->pathPrefix;
    }

    public function setPathPrefix(?string $pathPrefix): void
    {
        $this->pathPrefix = $pathPrefix;
    }
}
