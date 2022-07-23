<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class ExampleEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $nullField = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, nullable: false)]
    private ?string $requiredField = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNullField(): ?string
    {
        return $this->nullField;
    }

    public function setNullField(?string $nullField): void
    {
        /* Keep field null */
    }

    public function getRequiredField(): ?string
    {
        return $this->requiredField;
    }

    public function setRequiredField(?string $requiredField): void
    {
        $this->requiredField = $requiredField;
    }
}
