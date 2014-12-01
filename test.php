<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Reference;

require_once __DIR__.'/vendor/autoload.php';

$container = new ContainerBuilder();
$container
    ->register('Foo', 'Foo')
    ->addMethodCall('setToto', [3])
    ->addArgument(new Reference())
;

$container
    ->register('twig_loader', 'Twig_Loader_Array')
    ->addArgument([
        'foo' => 'hello {{ include("bar") }}',
        'bar' => 'world',
    ])
    ->setPublic(false)
;

$container
    ->register('twig', 'Twig_Environment')
    ->addArgument(new Reference('twig_loader'))
;

$dumper = new PhpDumper($container);

echo $dumper->dump();

echo $container->get('twig')->render('foo');
