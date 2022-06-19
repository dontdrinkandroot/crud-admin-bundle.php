<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class ExampleEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $nullField = null;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
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
