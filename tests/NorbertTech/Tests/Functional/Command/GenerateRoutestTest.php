<?php

declare(strict_types=1);

namespace NorbertTech\Calendar\Tests\Functional\Command;

use FixtureProject\Kernel;
use NorbertTech\StaticContentGeneratorBundle\Command\GenerateRoutesCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

final class GenerateRoutestTest extends KernelTestCase
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

    public function test_generating_content_from_all_routes() : void
    {
        $application = new Application(self::$kernel);

        $command = $application->find(GenerateRoutesCommand::NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--cli' => self::$kernel->getProjectDir() . '/bin/console',
        ]);

        $this->assertStringContainsString('Static content generated', $commandTester->getDisplay());
        $this->assertSame(0, $commandTester->getStatusCode());

        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/index.html');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/api.json');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/named/route/index.html');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/version/1.x/index.html');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/parametrized/first-param/second-param/index.html');
    }

    public function test_generating_content_from_specific_route() : void
    {
        $application = new Application(self::$kernel);

        $command = $application->find(GenerateRoutesCommand::NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--cli' => self::$kernel->getProjectDir() . '/bin/console',
            '--filter-route' => ['index_html'],
        ]);

        $this->assertStringContainsString('Static content generated', $commandTester->getDisplay());
        $this->assertSame(0, $commandTester->getStatusCode());

        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/index.html');
        $this->assertFileDoesNotExist(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/api.json');
        $this->assertFileDoesNotExist(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/named/route/index.html');
        $this->assertFileDoesNotExist(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/parametrized/first-param/second-param/index.html');
    }

    public function test_generating_content_except_specific_route() : void
    {
        $application = new Application(self::$kernel);

        $command = $application->find(GenerateRoutesCommand::NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--cli' => self::$kernel->getProjectDir() . '/bin/console',
            '--exclude-route' => ['api'],
        ]);

        $this->assertStringContainsString('Static content generated', $commandTester->getDisplay());
        $this->assertSame(0, $commandTester->getStatusCode());

        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/index.html');
        $this->assertFileDoesNotExist(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/api.json');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/api.xml');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/named/route/index.html');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/version/1.x/index.html');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/parametrized/first-param/second-param/index.html');
    }

    public function test_generating_content_except_specific_route_prefix() : void
    {
        $application = new Application(self::$kernel);

        $command = $application->find(GenerateRoutesCommand::NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--cli' => self::$kernel->getProjectDir() . '/bin/console',
            '--exclude-route-prefix' => ['api'],
        ]);

        $this->assertStringContainsString('Static content generated', $commandTester->getDisplay());
        $this->assertSame(0, $commandTester->getStatusCode());

        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/index.html');
        $this->assertFileDoesNotExist(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/api.json');
        $this->assertFileDoesNotExist(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/api.xml');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/named/route/index.html');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/version/1.x/index.html');
        $this->assertFileExists(self::$kernel->getContainer()->getParameter('static_content_generator.output_directory') . '/parametrized/first-param/second-param/index.html');
    }

    protected static function getKernelClass()
    {
        return Kernel::class;
    }
}
