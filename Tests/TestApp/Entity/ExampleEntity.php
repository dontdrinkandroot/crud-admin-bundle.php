<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 *
 * @author Philip Washington Sorst <philip@sorst.net>
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

    public function getId(): ?int
    {
        return $this->id;
    }
}
