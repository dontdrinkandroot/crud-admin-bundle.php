<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class        => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class         => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class          => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class                  => ['all' => true],
    Knp\Bundle\PaginatorBundle\KnpPaginatorBundle::class         => ['all' => true],
    Dontdrinkandroot\CrudAdminBundle\DdrCrudAdminBundle::class   => ['all' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['test' => true],
    Liip\TestFixturesBundle\LiipTestFixturesBundle::class        => ['test' => true],
];
