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
                        ->scalarNode('min_valid_in_row')
                            ->defaultValue(3)
                            ->info('Minimum of valid points in a row to start processing (greater than equals). Has to be greater than 0!')
                        ->end()
                        ->scalarNode('min_valid_points')
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
            ->end()
        ;

        return $treeBuilder;
    }
}
