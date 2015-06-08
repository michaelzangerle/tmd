<?php

namespace FHV\Bundle\TmdBundle\Controller;

use FHV\Bundle\TmdBundle\Exception\RestException;
use FHV\Bundle\TmdBundle\Exception\ResultNotFoundException;
use FHV\Bundle\TmdBundle\Manager\ResultManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use InvalidArgumentException;
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
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function patchAction($id, Request $request)
    {
        $data = $request->request->all();
        if (count($data) > 0) {
            try {
                $view = $this->view($this->getManager()->update($id, $data));
            } catch (ResultNotFoundException $ex) {
                $exc = new RestException($ex->getMessage());
                $view = $this->view($exc, 404);
            } catch (InvalidArgumentException $ex) {
                $exc = new RestException($ex->getMessage());
                $view = $this->view($exc, 400);
            }
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
