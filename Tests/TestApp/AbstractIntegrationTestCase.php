<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Dontdrinkandroot\Common\Asserted;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AbstractIntegrationTestCase extends WebTestCase
{
    protected ReferenceRepository $referenceRepository;

    protected KernelBrowser $kernelBrowser;

    protected function loadKernelAndFixtures(array $classNames = []): ReferenceRepository
    {
        $this->kernelBrowser = self::createClient();
        $databaseToolCollection = Asserted::instanceOf(
            self::getContainer()->get(DatabaseToolCollection::class),
            DatabaseToolCollection::class
        );
        $this->referenceRepository = $databaseToolCollection->get()->loadFixtures($classNames)->getReferenceRepository(
        );

        return $this->referenceRepository;
    }

    public static function getFormattedHtml(Crawler $crawler): string
    {
        $document = $crawler->getNode(0)->parentNode;
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
        $this->kernelBrowser->loginUser($user);
    }
}
