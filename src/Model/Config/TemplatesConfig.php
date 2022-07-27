<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model\Config;

use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\Serializer\Annotation\SerializedName;

class TemplatesConfig
{
    #[SerializedName('LIST')]
    private ?string $list = null;

    #[SerializedName('CREATE')]
    private ?string $create = null;

    #[SerializedName('READ')]
    private ?string $read = null;

    #[SerializedName('UPDATE')]
    private ?string $update = null;

    #[SerializedName('DELETE')]
    private ?string $delete = null;

    public function getList(): ?string
    {
        return $this->list;
    }

    public function setList(?string $list): void
    {
        $this->list = $list;
    }

    public function getCreate(): ?string
    {
        return $this->create;
    }

    public function setCreate(?string $create): void
    {
        $this->create = $create;
    }

    public function getRead(): ?string
    {
        return $this->read;
    }

    public function setRead(?string $read): void
    {
        $this->read = $read;
    }

    public function getUpdate(): ?string
    {
        return $this->update;
    }

    public function setUpdate(?string $update): void
    {
        $this->update = $update;
    }

    public function getDelete(): ?string
    {
        return $this->delete;
    }

    public function setDelete(?string $delete): void
    {
        $this->delete = $delete;
    }

    public function getByCrudOperation(CrudOperation $crudOperation): ?string
    {
        return match ($crudOperation) {
            CrudOperation::LIST => $this->getList(),
            CrudOperation::CREATE => $this->getCreate(),
            CrudOperation::READ => $this->getRead(),
            CrudOperation::UPDATE => $this->getUpdate(),
            CrudOperation::DELETE => $this->getDelete(),
        };
    }
}
