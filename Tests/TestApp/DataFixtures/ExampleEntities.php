<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;

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
        for ($i = 0; $i < 20; $i++) {
            $exampleEntity = new ExampleEntity();
            $manager->persist($exampleEntity);
            $this->addReference('example-entity-' . $i, $exampleEntity);
        }

        $manager->flush();
    }
}
