# Commandes

Permet d'executer des traitements en mode ligne de commande

## Fonctionnement

### Symfony\Component\Console\Command\Command

Classe contenant toute la logique de la commande

Elle permet de :

 * Définir une commande (nom, description arguments, options): ``Command::configure(): ``
 * Executer une commande: ``Command:execute(InputInterface, OutputInterface): ``

### Symfony\Component\Console\Input\InputInterface

Interface permettant la définition et la récupération des données saisies par l'utilisateur à l'exécution de la commande.

### Symfony\Component\Console\Input\OuputInterface

Interface permettant la gestion du feedback utilisateur, sur l'exécution de la commande, dans l'interpréteur de commandes.

### Symfony\Component\Console\Application

Container de commandes.

Elle permet de :
 * Ajouter une commande ``Application::add(Command)``
 * Rendre les commandes executables en ligne de commandes ``Application::run()``


## Exercice 1

### Énoncé

Je veux pouvoir executer une commande nommée ``hello``, qui m'affiche ``Hello world!`` dans l'output.

### Réponse

#### Code

```
<?php

# ./test.php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
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
```

#### Interpréteur de commandes

``php ./test.php`` retourne la liste des commandes.

``php ./test.php hello`` affiche ``Hello world!``.


## Configuration d'une commande

La méthode ``Command::configure()`` permet de définir :

 * son nom : ``Command::setName($name)``
 * sa description :  ``Command::setDescription($description)``
 * ses arguments : ``Command:addArgument($name, $mode, $description, $default)``
 * ses options : ``Command::addOption($name, $shortcut, $mode, $description, $default)``

Il existe différents modes (``$mode``), permettant de :

 * rendre l'argument obligatoire : ``InputArgument::REQUIRED``
 * rendre l'argument facultatif : ``InputArgument::OPTIONAL``
 * passer un tableau à l'argument : ``InputArgument::IS_ARRAY``
 * rendre l'option obligatoire : ``InputOption::VALUE_REQUIRED`` (par défaut, une option est facultative !)
 * rendre l'option facultative : ``InputArgument::VALUE_OPTIONAL``
 * passer un tableau à l'option : ``InputArgument::VALUE_IS_ARRAY``

## Traitement des arguments et options

Les arguments (exemple : ``my-command <argument_1> <argument_2>``) passés à la commande sont récupérables par l'intermédiaire de l'``InputInterface`` :

 * ``InputInterface::getArguments()`` retourne un tableau des arguments, ainsi que le nom de la commande (clé ``command``)
 * ``InputInterface::getArgument($name)`` retourne la valeur d'un argument en particulier

Les options (exemple : ``--foo=bar``) passés à la commande sont récupérables par l'intermédiaire de l'``InputInterface`` :

 * ``InputInterface::getOptions()`` retourne un tableau des options, ainsi que les options par défaut (exemples : ``help``, ``verbose``)
 * ``InputInterface::getOption($name)`` retourne la valeur d'une option en particulier

## Exercice 2

### Énoncé

Je veux pouvoir executer une commande nommée ``hello``, qui m'affiche ``Hello world!`` dans l'output.
Je dois pouvoir indiquer un nom après la commande. Si tel est le cas, je veux afficher ``Hello <username>!``.

### Réponse

#### Code

```
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
            ->addArgument('username', InputArgument::OPTIONAL, 'Who do you want to say hello?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username') ?: 'world';

        $output->writeln(sprintf('Hello %s!', $username));
    }
}

$application = new Application();
$application->add(new HelloCommand());
$application->run();
```

#### Interpréteur de commandes

``php ./test.php hello`` affiche ``Hello world!``.

``php ./test.php hello Bob`` affiche ``Hello Bob!``.


## Exercice 3

### Énoncé

Je veux pouvoir executer une commande nommée ``hello``, qui m'affiche ``Hello world!`` dans l'output.
Je dois pouvoir indiquer un nom dans la commande. Si tel est le cas, je veux afficher ``Hello <username>!``.
Je doit indiquer la langue dans laquelle j'affiche le message :
 - en anglais (en) par défaut : ``Hello <username>!``
 - en français (fr) : ``Bonjour <username> !``
 - en espagnol (es) : ``¡ Hola <username> !``


### Réponse

#### Code

```
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
```

#### Interpréteur de commandes

``php ./test.php hello`` affiche ``Hello world!``.

``php ./test.php hello Bob`` affiche ``Hello Bob!``.

``php ./test.php hello Bob --lang=fr`` affiche ``Bonjour Bob !``.
