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
        $container->setParameter('tmd.transport_modes', $config['transport_modes']);

        $this->setFilterParameters($container, $config);
        $this->setAnalyseParameters($container, $config);
        $this->setSegmentationParameters($container, $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'fhv_tmd';
    }

    /**
     * Sets the parameters needed for the filter operations
     *
     * @param $container
     * @param $config
     */
    protected function setFilterParameters($container, $config)
    {
        $container->setParameter(
            'tmd.filter.max_distance',
            $config['filter']['max_distance']
        );
        $container->setParameter(
            'tmd.filter.min_distance',
            $config['filter']['min_distance']
        );
        $container->setParameter(
            'tmd.filter.max_altitude_change',
            $config['filter']['max_altitude_change']
        );
        $container->setParameter(
            'tmd.filter.min_trackpoints_per_segment',
            $config['filter']['min_trackpoints_per_segment']
        );
        $container->setParameter(
            'tmd.filter.min_time_difference',
            $config['filter']['min_time_difference']
        );
        $container->setParameter(
            'tmd.filter.points_to_skip_from_start',
            $config['filter']['points_to_skip_from_start']
        );
        $container->setParameter(
            'tmd.filter.min_valid_points',
            $config['filter']['min_valid_points_ratio']
        );
        $container->setParameter(
            'tmd.filter.max_velocity_for_nearly_stoppoints',
            $config['filter']['max_velocity_for_nearly_stoppoints']
        );
        $container->setParameter(
            'tmd.filter.max_time_without_movement',
            $config['filter']['max_time_without_movement']
        );
    }

    /**
     * Sets the parameters needed for the analyse operations
     *
     * @param $container
     * @param $config
     */
    protected function setAnalyseParameters($container, $config)
    {
        $container->setParameter('tmd.analyse', $config['analyse']);
    }

    /**
     * Sets the parameters needed for the segmentation
     *
     * @param $container
     * @param $config
     */
    protected function setSegmentationParameters($container, $config)
    {
        // walk point detection
        $container->setParameter(
            'tmd.segmentation.max_walk_velocity',
            $config['segmentation']['max_walk_velocity']
        );
        $container->setParameter(
            'tmd.segmentation.max_walk_acceleration',
            $config['segmentation']['max_walk_acceleration']
        );

        // segment creation
        $container->setParameter(
            'tmd.segmentation.min_segment_distance',
            $config['segmentation']['min_segment_distance']
        );
        $container->setParameter(
            'tmd.segmentation.min_segment_time',
            $config['segmentation']['min_segment_time']
        );
        $container->setParameter(
            'tmd.segmentation.max_time_difference',
            $config['segmentation']['max_time_difference']
        );

        // stop point detection
        $container->setParameter(
            'tmd.segmentation.max_time_without_movement',
            $config['segmentation']['max_time_without_movement']
        );
        $container->setParameter(
            'tmd.segmentation.max_velocity_for_nearly_stoppoints',
            $config['segmentation']['max_velocity_for_nearly_stoppoints']
        );

        // uncertain / certain segment handling
        $container->setParameter(
            'tmd.segmentation.certain_segments_min_time',
            $config['segmentation']['certain_segments_min_time']
        );
        $container->setParameter(
            'tmd.segmentation.certain_segments_min_distance',
            $config['segmentation']['certain_segments_min_distance']
        );
    }
}
