<?php

declare(strict_types=1);

namespace NorbertTech\Calendar\Tests\Functional\Command;

use FixtureProject\Kernel;
use NorbertTech\StaticContentGeneratorBundle\Command\CopyAssetsCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

final class CopyAssetsCommanndTest extends KernelTestCase
{
    public function setUp() : void
    {
        self::bootKernel();

        (new Filesystem())->remove(
            self::$kernel->getContainer()->getParameter('static_content_generator.output_directory')
        );
    }

    public function tearDown() : void
    {
        self::bootKernel();

        (new Filesystem())->remove(
            self::$kernel->getContainer()->getParameter('static_content_generator.output_directory')
        );
    }

    public function test_copy_assets() : void
    {
        $application = new Application(self::$kernel);

        $command = $application->find(CopyAssetsCommand::NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertStringContainsString('Assets copied', $commandTester->getDisplay());
        $this->assertSame(0, $commandTester->getStatusCode());

        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/assets/js/scripts.js');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/assets/js/scripts.min.js');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/assets/css/style.css');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/assets/CNAME');
    }

    protected static function getKernelClass()
    {
        return Kernel::class;
    }
}
