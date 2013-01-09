<?php
namespace Spyrit\Silex\Utils\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Spyrit Silex Propel Build command
 *
 * @author Charles Sanquer <charles.sanquer@spyrit.net>
 *
 */
class PropelBuildCommand extends Command
{

    protected function configure()
    {
        $this
                ->setName('propel:build')
                ->setDescription('Spyrit Silex Propel Build command')
//            ->addArgument('name', InputArgument::OPTIONAL, '')
//            ->addOption('opt', null, InputOption::VALUE_NONE, '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Build Model and Configuration</info>');

        $process = new Process($this->getApplication()->getBinDir().DS.'propel-gen '.$this->getApplication()->getAppDir().DS.'config main');
        $process->run(function ($type, $buffer) {
            if ('err' === $type) {
                echo 'ERROR: '.$buffer;
            } else {
                echo $buffer;
            }
        });
    }

}
