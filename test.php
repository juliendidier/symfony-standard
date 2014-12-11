<?php

# test.php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once __DIR__.'/vendor/autoload.php';

class HelloCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('hello')
            ->addArgument('username', InputArgument::IS_ARRAY, 'Who do you want to say hello?')
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

        $lang = $input->getOption('lang');

        if (false === array_key_exists($lang, $patterns)) {
            throw new \LogicException(sprintf('"%s" is not defined, available languages: "%s"', $lang, implode('", "', array_keys($patterns))));
        }

        $pattern = $patterns[$input->getOption('lang')];

        foreach ($input->getArgument('username') as $username) {
            $output->writeln(sprintf($pattern, $username));
        }
    }
}

$application = new Application();
$application->add(new HelloCommand());
$application->run();
