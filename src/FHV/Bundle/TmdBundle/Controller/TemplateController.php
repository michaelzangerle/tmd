<?php

namespace FHV\Bundle\TmdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Template controller which provides templates for the app
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
        return $this->render(
            'FHVTmdBundle:Theme:index.html.twig',
            array(
                'currentNavigation' => 'index',
            )
        );
    }

    /**
     * Returns the template for the create page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        return $this->render(
            'FHVTmdBundle:Theme:create.html.twig',
            array(
                'currentNavigation' => 'create',
            )
        );
    }

    /**
     * Returns the template for the analytics page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function analyseAction()
    {
        return $this->render(
            'FHVTmdBundle:Theme:analyse.html.twig',
            array(
                'currentNavigation' => 'analyse',
            )
        );
    }
}
