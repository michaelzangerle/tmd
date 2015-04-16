<?php

namespace FHV\Bundle\TmdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TemplateController extends Controller
{
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
