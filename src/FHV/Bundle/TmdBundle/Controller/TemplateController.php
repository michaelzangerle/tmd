<?php

namespace FHV\Bundle\TmdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TemplateController
 * @package FHV\Bundle\TmdBundle\Controller
 */
class TemplateController extends Controller
{
    /**
     * Returns the template for the index page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $name = 'default';

        return $this->render(
            'FHVTmdBundle:Theme:index.html.twig',
            array(
                'name' => $name,
                'currentNavigation' => 'index'
            )
        );
    }

    /**
     * Returns the template for the create page
     *
     * @param string $name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction($name = 'default')
    {
        return $this->render(
            'FHVTmdBundle:Theme:create.html.twig',
            array(
                'name' => $name,
                'currentNavigation' => 'create'
            )
        );
    }

    /**
     * Returns the template for the analytics page
     *
     * @param string $name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function analyticsAction($name = 'default')
    {
        return $this->render(
            'FHVTmdBundle:Theme:analytics.html.twig',
            array(
                'name' => $name,
                'currentNavigation' => 'analytics'
            )
        );
    }
}
