<?php

# test.php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__.'/vendor/autoload.php';

class HelloCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('hello')
            ->addArgument('username', InputArgument::OPTIONAL, 'Who do you want to say hello?', 'world')
            ->addOption('lang', null, InputOption::VALUE_REQUIRED, 'What language would you want to say hello?', 'en')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $patterns = [
            'en' => 'Hello %s!',
            'fr' => 'Bonjour %s !',
            'es' => '¡ Hola %s !',
        ];

        $pattern = $patterns[$input->getOption('lang')];
        $username = $input->getArgument('username');

        $output->writeln(sprintf($pattern, $username));
    }
}

$application = new Application();
$application->add(new HelloCommand());
$application->run();
