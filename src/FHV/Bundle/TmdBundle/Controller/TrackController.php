<?php

namespace FHV\Bundle\TmdBundle\Controller;

use FHV\Bundle\TmdBundle\Entity\Track;
use FHV\Bundle\TmdBundle\Manager\TrackManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        // TODO just a dummy implementation
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
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        if ($this->isValidFile($file)) {
            $timeStamp = (new \DateTime('now'))->format('U');
            $fileName = substr(trim($file->getClientOriginalName()), 0, 60); // TODO security issue with file name
            $method = $request->get('method', $this->container->getParameter('tmd.analyze.default_method'));
            $xmlFile = $file->move(__DIR__ . '/../uploaded', $timeStamp . '_' . $fileName);

            $track = $this->getManager()->create($xmlFile, $method);
            $view = $this->view($track, 200); // TODO correct response
        } else {
            $view = $this->view('GPS file is incomplete or not valid!', 400);
        }

        // TODO remove file when finished?
        return $this->handleView($view);
    }

    /**
     * Checks if the uploaded file is valid
     *
     * @param UploadedFile $file
     *
     * @return bool
     */
    private function isValidFile(UploadedFile $file)
    {
        if ($file != null && $file->isValid() &&
            $file->getMimeType() === 'application/xml' &&
            $file->getMaxFilesize() > $file->getSize()
        ) {
            return true;
        }

        return false;
    }

    /**
     * Returns a track manager implementation
     *
     * @return TrackManagerInterface
     */
    protected function getManager()
    {
        return $this->get('fhv_tmd.trackManager');
    }
}
