<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;

class ExampleEntities extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $suffix = str_pad((string)$i, 5, 0, STR_PAD_LEFT);
            $exampleEntity = new ExampleEntity(
                requiredReadonly: 'requiredReadonly' . $suffix,
                required: 'required' . $suffix,
                requiredNullable: null
            );
            $manager->persist($exampleEntity);
            $this->addReference('example-entity-' . $i, $exampleEntity);
        }

        $manager->flush();
    }
}
