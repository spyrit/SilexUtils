<?php

namespace Spyrit\Silex\Utils\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Spyrit Silex Tools cache cleaning command
 *
 * @author Charles Sanquer <charles.sanquer@spyrit.net>
 *
 */
class CacheClearCommand extends Command
{

    protected function configure()
    {
        $this
                ->setName('cache:clear')
                ->setDescription('Spyrit SilexSandbox twig cache cleaning command')
//            ->addArgument('name', InputArgument::OPTIONAL, '')
//            ->addOption('opt', null, InputOption::VALUE_NONE, '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $fs = new Filesystem();
        
        $output->writeln('Clearing Application cache ...');
        
        $cacheDir = $this->getApplication()->getProjectDir().'/app/cache';
        
        if (!file_exists($cacheDir)) {
            $fs->mkdir($cacheDir);
        }
        
        $finder->directories()->depth('== 0')->in($cacheDir);
        foreach ($finder as $file) {
            $output->writeln('remove '.$file->getRelativePathname());
            $fs->remove($file->getRealpath());
        }
    }

}
