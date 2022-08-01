<?php

namespace Dontdrinkandroot\CrudAdminBundle\Model\Config;

class DefaultSortConfig
{
    private ?string $field = null;

    private string $order = 'asc';

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(?string $field): DefaultSortConfig
    {
        $this->field = $field;
        return $this;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function setOrder(string $order): DefaultSortConfig
    {
        $this->order = $order;
        return $this;
    }
}
