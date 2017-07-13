<?php

namespace MC\StreamsAPI\Bootstrap;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Responsible for managing the creation of the container
 */
class Container
{
    const PRIMARY_CONFIG_FILE = 'config/config.yml';

    /**
     * @param string $root
     * @return ContainerInterface
     */
    public static function load(string $root) : ContainerInterface
    {
        $container = new ContainerBuilder();
        $builder = new YamlFileLoader($container, new FileLocator($root));
        $builder->load(static::PRIMARY_CONFIG_FILE);

        // add container so that routes can be lazy loaded
        $container->set('container', $container);

        // in a real application, we would cache the container for faster loading
        $container->compile();

        return $container;
    }
}
