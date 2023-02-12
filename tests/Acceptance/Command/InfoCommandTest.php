<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\Acceptance\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class InfoCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('ddr:crud-admin:info');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertNotEmpty($output);
    }
}
