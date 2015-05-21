<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Manager;

/**
 * Class DecissionTreeDumper
 * @package FHV\Bundle\TmdBundle\DecisionTree\Manager
 */
class DecisionTreeDumper
{
    /**
     * Renders twig template
     * @param array $config
     * @param array $dt
     * @return string
     */
    public function dump(array $config, array $dt)
    {
        return $this->render(
            'DecisionTreeClass.php.twig',
            array(
                'cache_class' => $config['cache_class'],
                'tree' => $dt,
            )
        );
    }

    /**
     * @param $template
     * @param $parameters
     * @return string
     */
    protected function render($template, $parameters)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__ . '/../../Resources/skeleton/'));
        return $twig->render($template, $parameters);
    }
}
