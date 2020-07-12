<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Command;

use Aeon\Calendar\Stopwatch;
use NorbertTech\StaticContentGeneratorBundle\Assets\Asset;
use NorbertTech\StaticContentGeneratorBundle\Assets\Assets;
use NorbertTech\StaticContentGeneratorBundle\Assets\RecursiveDirectoryIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CopyAssetsCommand extends Command
{
    public const NAME = 'static-content-generator:copy:assets';

    protected static $defaultName = self::NAME;

    private string $projectDir;

    private Assets $assets;

    public function __construct(string $projectDir, Assets $assets)
    {
        parent::__construct(self::NAME);
        $this->projectDir = $projectDir;
        $this->assets = $assets;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Copy all static files from one directory to another')
            ->addOption('public-dir', 'd', InputArgument::OPTIONAL, 'Directory relative to %kernel.project_dir% from which assets should be copied to output directory', 'public')
            ->addOption('ignore', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Ignore files with given extension', ['php']);
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Copy assets');

        $publicDirector = $this->projectDir . DIRECTORY_SEPARATOR . $input->getOption('public-dir');

        $iterator = new RecursiveDirectoryIterator($publicDirector, $input->getOption('ignore'));

        $stopwatch = new Stopwatch();
        $stopwatch->start();

        $progressBar = $io->createProgressBar();

        $iterator->each(function (Asset $file) use ($progressBar, $io, $output) : void {
            $this->assets->copy($file);

            if ($output->isVerbose()) {
                $io->note('Generated content: ' . $file->relativePath());
            }

            $progressBar->advance();
        });

        $stopwatch->stop();
        $progressBar->finish();

        $io->newLine(2);

        $io->success('Assets copied');

        $io->writeln(\sprintf('Copying time: %s seconds', $stopwatch->totalElapsedTime()->inSecondsPrecise()));

        return 0;
    }
}
