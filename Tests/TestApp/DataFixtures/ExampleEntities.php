<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Faker\Factory;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ExampleEntities extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $exampleEntity = new ExampleEntity();
            $exampleEntity->setRequiredField(str_pad((string)$i, 5, 0, STR_PAD_LEFT));
            $manager->persist($exampleEntity);
            $this->addReference('example-entity-' . $i, $exampleEntity);
        }

        $manager->flush();
    }
}
