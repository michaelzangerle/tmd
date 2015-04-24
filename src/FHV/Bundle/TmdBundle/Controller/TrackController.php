<?php

namespace FHV\Bundle\TmdBundle\Controller;

use FHV\Bundle\TmdBundle\Entity\Track;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TrackController
 * @package FHV\Bundle\TmdBundle\Controller
 */
class TrackController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @param $id
     *
     * @return Response
     */
    public function getAction($id){

        $view = $this->view(new Track(), 200);
        return $this->handleView($view);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function postAction(Request $request){

        $view = $this->view(new Track(), 200);
        return $this->handleView($view);
    }
}
