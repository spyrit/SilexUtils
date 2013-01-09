<?php
namespace Spyrit\Silex\Utils\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Spyrit Silex Propel Insert SQL command
 *
 * @author Charles Sanquer <charles.sanquer@spyrit.net>
 *
 */
class PropelInsertSqlCommand extends PropelAbstractSqlCommand
{

    protected function configure()
    {
        $this
            ->setName('propel:sql:insert')
            ->setDescription('Spyrit Silex Propel Insert SQL command')
//            ->addArgument('name', InputArgument::OPTIONAL, '')
//            ->addOption('opt', null, InputOption::VALUE_NONE, '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Create database ...</info>');
        
        $finder = new Finder();
        
        $pdo = $this->getDefaultConnection();
        
        $finder->files()->name('*.sql')->in($this->getApplication()->getAppDir().'/sql');
        foreach ($finder as $file) {
//            $file = new \Symfony\Component\Finder\SplFileInfo();
            $output->writeln('read '.$file->getRelativePathname());
            
            $sql = file_get_contents($file->getRealPath());
            if ($pdo->query($sql) !== false) {
                $output->writeln('<info>'.$file->getRelativePathname().' inserted</info>');
            }
        }
    }

}
