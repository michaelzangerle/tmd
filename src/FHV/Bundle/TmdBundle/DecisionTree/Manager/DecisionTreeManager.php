<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Manager;

use FHV\Bundle\TmdBundle\DecisionTree\Model\Tree;
use Symfony\Component\Config\ConfigCache;

/**
 * Triggers the generation of a new decision tree file if its out of date or does not exist
 * Class DecisionTreeManager
 * @package FHV\Bundle\TmdBundle\DecisionTree\Manager
 */
class DecisionTreeManager implements DecisionTreeManagerInterface
{

    /**
     * @var array
     */
    protected $trees = [];

    /**
     * @var array
     */
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Returns a tree and writes cache file if it does not exist
     *
     * @param $name
     *
     * @return Tree
     */
    public function getTree($name)
    {
        if (array_key_exists($name, $this->trees)) {
            return $this->trees[$name];
        } else {

            if (!array_key_exists($name, $this->config)) {
                throw new \InvalidArgumentException('No config for the key '.$name.' found!');
            }

            $config = $this->config[$name];
            $cache = new ConfigCache($config['cacheDir'].'/'.$config['class'].'.php', true);

            if (!$cache->isFresh()) {
                $dtb = new DecisionTreeBuilder($config['txtFilePath'], $config['txtFileName']);
                $result = $dtb->build();
                $dumper = new DecisionTreeDumper();
                $cache->write(
                    $dumper->dump(
                        array(
                            'cache_class' => $config['class'],
                        ),
                        $result['tree']
                    ),
                    $result['resource']
                );
            }

            require_once $cache;
            $this->trees[$name] = new $config['class']();

            return $this->trees[$name];
        }
    }
}
