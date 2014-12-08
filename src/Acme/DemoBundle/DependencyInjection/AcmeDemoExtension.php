<?php

namespace Acme\DemoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AcmeDemoExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $loadersDefitions = [];

        if (null !== $config['static']) {
            $def = new Definition('Twig_Loader_Array');
            $def
                ->addArgument($config['static'])
                ->setPublic(false)
            ;

            $loadersDefitions[] = $def;
        }

        if (null !== $config['directory']) {
            $def = new Definition('Twig_Loader_Filesystem');
            $def
                ->addArgument([$config['directory']])
                ->setPublic(false)
            ;

            $loadersDefitions[] = $def;
        }

        if (count($loadersDefitions) > 1) {
            $container
                ->register('my_twig_loader', 'Twig_Loader_Chain')
                ->addArgument([$loadersDefitions])
                ->setPublic(false)
            ;
        } else {
            $container
                ->setDefinition('my_twig_loader', $lodader[0])
            ;
        }

        $container
            ->register('my_twig', 'Twig_Environment')
            ->addArgument(new Reference('my_twig_loader'))
        ;
    }

    public function getAlias()
    {
        return 'acme_demo';
    }
}
