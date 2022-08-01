<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $id;

    public function __construct(
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[ORM\Column(type: Types::STRING, nullable: false)]
        public string $name,

        #[ORM\Column(type: Types::STRING, nullable: true)]
        public ?string $phonePrefix = null
    ) {
    }
}
