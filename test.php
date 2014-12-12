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

class UnsupportedLanguageException extends \LogicException {}

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

    public static function getAvailablePatterns()
    {
        return [
            'en' => 'Hello %s!',
            'fr' => 'Bonjour %s !',
            'es' => '¡ Hola %s !',
        ];
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lang = $input->getOption('lang');
        $patterns = self::getAvailablePatterns();

        if (false === array_key_exists($lang, $patterns)) {
            throw new UnsupportedLanguageException(sprintf('Language "%s" is not supported', $lang));
        }

        $pattern = $patterns[$input->getOption('lang')];

        foreach ($input->getArgument('username') as $username) {
            $output->writeln(sprintf($pattern, $username));
        }
    }
}

$dispatcher = new EventDispatcher();
$dispatcher->addListener(ConsoleEvents::EXCEPTION, function (ConsoleExceptionEvent $event) {
    $output = $event->getOutput();
    $exception = $event->getException();

    if (false === $output->isVeryVerbose()) {
        return;
    }

    if (!$exception instanceof UnsupportedLanguageException) {
        return;
    }

    $command = $event->getCommand();
    $message = sprintf('%s (supported languages: "%s")',
        $exception->getMessage(),
        implode('", "', array_keys($command::getAvailablePatterns()))
    );

    $e = new UnsupportedLanguageException($message);
    $event->setException($e);
});

$application = new Application();
$application->add(new HelloCommand());
$application->setDispatcher($dispatcher);
$application->run();
