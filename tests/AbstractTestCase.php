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
    protected ReferenceRepository $referenceRepository;

    protected KernelBrowser $client;

    protected function loadClientAndFixtures(array $classNames = []): ReferenceRepository
    {
        $this->client = self::createClient();
        $databaseToolCollection = Asserted::instanceOf(
            self::getContainer()->get(DatabaseToolCollection::class),
            DatabaseToolCollection::class
        );
        $this->referenceRepository = $databaseToolCollection->get()
            ->loadFixtures($classNames)
            ->getReferenceRepository();

        return $this->referenceRepository;
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

    protected function logIn(string $identifier): void
    {
        $userProvider = Asserted::instanceOf(
            self::getContainer()->get(UserProviderInterface::class),
            UserProviderInterface::class
        );
        $user = $userProvider->loadUserByIdentifier($identifier);
        $this->client->loginUser($user);
    }
}
