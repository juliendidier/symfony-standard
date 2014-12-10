<?php

# test.php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__.'/vendor/autoload.php';


class HelloCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('hello')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello world!');
    }
}

$application = new Application();
$application->add(new HelloCommand());
$application->run();
