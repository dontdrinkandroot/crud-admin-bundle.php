<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Dontdrinkandroot\Common\Asserted;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class AbstractIntegrationTestCase extends WebTestCase
{
    protected ReferenceRepository $referenceRepository;

    protected KernelBrowser $kernelBrowser;

    protected function loadKernelAndFixtures(array $classNames = []): ReferenceRepository
    {
        $this->kernelBrowser = self::createClient();
        $databaseToolCollection = Asserted::instanceOf(
            self::$container->get(DatabaseToolCollection::class),
            DatabaseToolCollection::class
        );
        $this->referenceRepository = $databaseToolCollection->get()->loadFixtures($classNames)->getReferenceRepository(
        );

        return $this->referenceRepository;
    }

    protected function logIn(string $username)
    {
        $session = self::$container->get('session');

        $userProvider = self::$container->get(UserProviderInterface::class);
        assert($userProvider instanceof UserProviderInterface);
        $user = $userProvider->loadUserByUsername($username);

        $firewallName = 'main';
        $firewallContext = 'main';

        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->kernelBrowser->getCookieJar()->set($cookie);
    }

    public static function getFormattedHtml(Crawler $crawler): string
    {
        $document = $crawler->getNode(0)->parentNode;
        $document->formatOutput = true;
        return $document->saveHtml($document);
    }
}
