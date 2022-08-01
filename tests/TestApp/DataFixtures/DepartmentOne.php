<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;

class DepartmentOne extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $department = new Department('one');
        $manager->persist($department);
        $manager->flush();
        $this->addReference(self::class, $department);
    }
}
