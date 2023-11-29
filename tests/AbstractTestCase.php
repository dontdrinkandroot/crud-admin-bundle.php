<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use DOMDocument;
use Dontdrinkandroot\Common\Asserted;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AbstractTestCase extends WebTestCase
{
    protected static function loadFixtures(array $classNames = []): ReferenceRepository
    {
        return self::getService(DatabaseToolCollection::class)->get()
            ->loadFixtures($classNames)
            ->getReferenceRepository();
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    protected static function getService(string $class): object
    {
        $service = self::getContainer()->get($class);
        self::assertInstanceOf($class, $service);
        return $service;
    }

    public static function getFormattedHtml(Crawler $crawler): string
    {
        $document = Asserted::instanceOf(
            Asserted::notNull($crawler->getNode(0))->parentNode,
            DOMDocument::class
        );
        $document->formatOutput = true;
        return $document->saveHtml($document);
    }

    protected static function logIn(KernelBrowser $client, string $identifier): void
    {
        $userProvider = Asserted::instanceOf(
            self::getContainer()->get(UserProviderInterface::class),
            UserProviderInterface::class
        );
        $user = $userProvider->loadUserByIdentifier($identifier);
        $client->loginUser($user);
    }
}
