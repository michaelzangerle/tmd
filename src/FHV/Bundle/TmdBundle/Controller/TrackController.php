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
    public function getAction($id)
    {

        $view = $this->view(new Track(), 200);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function postAction(Request $request)
    {
        $file = $request->files->get('file');
        if ($file->isValid() && $file->getMimeType() === 'application/xml') {
            $content = file_get_contents($file->getPathName());
            $method = $request->get('method', $this->container->getParameter('tmd.analyze.default_method'));

            // TODO start processing

            $view = $this->view('', 204);
        } else {

            $view = $this->view('GPS file is incomplete or not valid!', 400);
        }

        // TODO remove file when finished
        return $this->handleView($view);
    }
}
