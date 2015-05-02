<?php

namespace FHV\Bundle\TmdBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fhv_tmd');

        $rootNode
            ->children()
                ->arrayNode('filter')
                    ->children()
                        ->scalarNode('max_distance')
                            ->defaultValue(45)
                            ->info('Maximum distance between two trackpoints in meters per second (less than).')
                        ->end()
                        ->scalarNode('min_distance')
                            ->defaultValue(0)
                            ->info('Minimum distance between two trackpoints in meters per second (greater than).')
                        ->end()
                        ->scalarNode('max_altitude_change')
                            ->defaultValue(10)
                            ->info('Maximum change in altitude between two trackpoints in meters per second (less than).')
                        ->end()
                        ->scalarNode('min_trackpoints_per_segment')
                            ->defaultValue(2)
                            ->info('Minimum number of trackpoins needed for a valid segment (greater than equals).')
                        ->end()
                        ->scalarNode('min_time_difference')
                            ->defaultValue(1)
                            ->info('Minimum difference of time in seconds between two trackpoints (greater than equals). Has to be greater than 0!')
                        ->end()
                        ->scalarNode('points_to_skip_from_start')
                            ->defaultValue(3)
                            ->info('Amount of points that should be skipped at the start!')
                        ->end()
                        ->scalarNode('min_valid_points_ratio')
                            ->defaultValue(0.85)
                            ->info('Minimum of valid points per track in percentage (greater than equals).')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('planet_circumference')
                    ->defaultValue(6371001)
                    ->info('Circumference of the planet.')
                ->end()
                ->scalarNode('gpx_namespace')
                    ->defaultValue('http://www.topografix.com/GPX/1/1')
                    ->info('Namespace for the gpx files.')
                ->end()
                ->arrayNode('segmentation')
                    ->children()
                        ->scalarNode('max_walk_velocity')
                            ->defaultValue(3)
                            ->info('Max velocity for walk points in (m/s).')
                        ->end()
                        ->scalarNode('max_walk_acceleration')
                            ->defaultValue(10)
                            ->info('Max acceleration for walk points in (m/s2)')
                        ->end()
                        ->scalarNode('min_segment_distance')
                            ->defaultValue(100)
                            ->info('Minimal distance of a segment in meters.')
                        ->end()
                        ->scalarNode('min_segment_time')
                            ->defaultValue(10)
                            ->info('Minimal time of a segment in seconds.')
                        ->end()
                        ->scalarNode('max_time_difference')
                            ->defaultValue(30)
                            ->info('Maximum time difference in seconds between two track points.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('analyze')
                    ->children()
                        ->scalarNode('default_method')
                            ->defaultValue(1)
                            ->info('Default method to analyze data.')
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
