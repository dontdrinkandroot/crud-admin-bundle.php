<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model\Config;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Symfony\Component\Serializer\Annotation\SerializedName;

class FieldDefinitionsConfig
{
    /** @var list<FieldDefinition> */
    #[SerializedName('LIST')]
    private ?array $list = null;

    /** @var list<FieldDefinition> */
    #[SerializedName('CREATE')]
    private ?array $create = null;

    /** @var list<FieldDefinition> */
    #[SerializedName('READ')]
    private ?array $read = null;

    /** @var list<FieldDefinition> */
    #[SerializedName('UPDATE')]
    private ?array $update = null;

    /** @var list<FieldDefinition> */
    #[SerializedName('DELETE')]
    private ?array $delete = null;

    public function getList(): ?array
    {
        return $this->list;
    }

    public function setList(?array $list): void
    {
        $this->list = $list;
    }

    public function getCreate(): ?array
    {
        return $this->create;
    }

    public function setCreate(?array $create): void
    {
        $this->create = $create;
    }

    public function getRead(): ?array
    {
        return $this->read;
    }

    public function setRead(?array $read): void
    {
        $this->read = $read;
    }

    public function getUpdate(): ?array
    {
        return $this->update;
    }

    public function setUpdate(?array $update): void
    {
        $this->update = $update;
    }

    public function getDelete(): ?array
    {
        return $this->delete;
    }

    public function setDelete(?array $delete): void
    {
        $this->delete = $delete;
    }

    public function getByCrudOperation(CrudOperation $crudOperation): ?array
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
