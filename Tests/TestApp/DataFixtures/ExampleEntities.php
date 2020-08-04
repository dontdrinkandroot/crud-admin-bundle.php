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
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $faker->seed(939768);

        for ($i = 0; $i < 20; $i++) {
            $exampleEntity = new ExampleEntity();
            $exampleEntity->setRequiredField($faker->name);
            $manager->persist($exampleEntity);
            $this->addReference('example-entity-' . $i, $exampleEntity);
        }

        $manager->flush();
    }
}
