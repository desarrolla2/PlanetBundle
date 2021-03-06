<?php

namespace Desarrolla2\Bundle\PlanetBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PlanetExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('planet.newblog.name', $config['newblog']['name']);
        $container->setParameter('planet.newblog.email', $config['newblog']['email']);
        $container->setParameter('planet.newblog.title', $config['newblog']['title']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('newblog.xml');
        $loader->load('reporter.xml');
        $loader->load('post.xml');
        $loader->load('spider.xml');

    }
}
