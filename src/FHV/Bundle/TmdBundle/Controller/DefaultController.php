<?php

namespace FHV\Bundle\TmdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $name = 'default';

        return $this->render('FHVTmdBundle:Default:index.html.twig', array('name' => $name));
    }

    public function fooAction($name = 'default')
    {
        return $this->render('FHVTmdBundle:Default:index.html.twig', array('name' => $name));
    }
}
