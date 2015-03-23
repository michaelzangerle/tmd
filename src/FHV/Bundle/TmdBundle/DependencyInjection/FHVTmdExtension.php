<?php

namespace FHV\Bundle\TmdBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FHVTmdExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('tmd.planet_circumference', $config['planet_circumference']);
        $container->setParameter('tmd.gpx_namespace', $config['gpx_namespace']);

        $this->setFilterParameters($container, $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'fhv_tmd';
    }

    /**
     * Sets the parameters needed for the filter operations
     * @param $container
     * @param $config
     */
    private function setFilterParameters($container, $config)
    {
        $container->setParameter('tmd.filter.max_distance', $config['filter']['max_distance']);
        $container->setParameter('tmd.filter.min_distance', $config['filter']['min_distance']);
        $container->setParameter('tmd.filter.max_altitude_change', $config['filter']['max_altitude_change']);
        $container->setParameter('tmd.filter.min_trackpoints_per_segment', $config['filter']['min_trackpoints_per_segment']);
        $container->setParameter('tmd.filter.min_time_difference', $config['filter']['min_time_difference']);
        $container->setParameter('tmd.filter.max_velocity', $config['filter']['max_velocity']);
    }
}
