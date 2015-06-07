<?php

namespace FHV\Bundle\TmdBundle\Controller;

use FHV\Bundle\TmdBundle\Manager\ResultManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Result controller which provides a minimal REST api to update results
 * Class ResultController
 * @package FHV\Bundle\TmdBundle\Controller
 */
class ResultController extends FOSRestController implements ClassResourceInterface
{

    /**
     * Patch action for results
     *
     * @param         $id
     * @param Request $request
     *
     * @return Response
     */
    public function patchAction($id, Request $request)
    {
        $data = $request->request->all();
        if ($id !== null && count($data) > 0) {
            $view = $this->view($this->getManager()->update($id, $data));
        } else {
            $view = $this->view('Request is incomplete!', 400);
        }

        return $this->handleView($view);
    }

    /**
     * Returns a result manager implementation
     *
     * @return ResultManagerInterface
     */
    protected function getManager()
    {
        return $this->get('fhv_tmd.resultManager');
    }
}
