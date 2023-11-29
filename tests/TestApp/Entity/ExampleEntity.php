<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class ExampleEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private ?int $id = null;

    public function __construct(
        /* Doctrine does not support readonly public properties yet, change when fixed with symfony 6 */
        #[Assert\NotBlank]
        #[ORM\Column(type: 'string', nullable: false)]
        private readonly string $requiredReadonly,

        #[Assert\NotBlank]
        #[ORM\Column(type: 'string', nullable: false)]
        public string $required,

        #[ORM\Column(type: 'string', nullable: true)]
        public ?string $requiredNullable,

        #[Assert\NotBlank]
        #[ORM\Column(type: 'string', nullable: false)]
        public string $requiredWithDefault = 'defaultValue',

        #[ORM\Column(type: 'string', nullable: true)]
        public ?string $nullableWithDefault = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id ?? throw new RuntimeException('Entity not persisted');
    }

    public function getRequiredReadonly(): string
    {
        return $this->requiredReadonly;
    }
}
