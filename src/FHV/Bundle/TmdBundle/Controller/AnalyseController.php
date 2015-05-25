<?php

namespace FHV\Bundle\TmdBundle\Controller;

use FHV\Bundle\TmdBundle\Manager\AnalyseMangerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller which provides a minimal rest api to fetch the results of the analyse methods
 * Class AnalyseController
 * @package FHV\Bundle\TmdBundle\Controller
 */
class AnalyseController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Returns analyzed results for statistics
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(Request $request)
    {
        $type = $request->get('type', 'overview');

        if ($type === 'overview') {
            $view = $this->view($this->getManager()->getOverview());
        } elseif ($type === 'detail') {
            $mode = $request->get('mode');
            $view = $this->view($this->getManager()->getDetail($mode));
        } else {
            $view = $this->view('Provide a valid type (overview, detail) parameter!', 400);
        }

        return $this->handleView($view);
    }

    /**
     * Returns an analyse manager implementation
     *
     * @return AnalyseMangerInterface
     */
    protected function getManager()
    {
        return $this->get('fhv_tmd.analyseManager');
    }
}
