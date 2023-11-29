<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private ?int $id = null;

    public function __construct(
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[ORM\Column(type: Types::STRING, nullable: false)]
        public string $name,

        #[ORM\Column(type: Types::STRING, nullable: true)]
        public ?string $phonePrefix = null
    ) {
    }

    public function getId(): int
    {
        return $this->id ?? throw new RuntimeException('Entity not persisted');
    }
}
