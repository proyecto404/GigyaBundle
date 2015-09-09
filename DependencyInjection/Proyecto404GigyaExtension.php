<?php

namespace Proyecto404\GigyaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class Proyecto404GigyaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setAlias('gigya', 'proyecto404_gigya.api');

        foreach (array('api', 'twig') as $attribute) {
            $container->setParameter('proyecto404_gigya.'.$attribute.'.class', $config['class'][$attribute]);
        }

        foreach (array('api_key', 'secret_key') as $attribute) {
            $container->setParameter('proyecto404_gigya.'.$attribute, $config[$attribute]);
        }
    }
}
