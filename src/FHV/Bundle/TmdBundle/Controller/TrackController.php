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
     * Processes a post request for a new track
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
            $fileName = substr(
                trim($file->getClientOriginalName()),
                0,
                60
            ); // TODO possible security issue with file name?
            $method = $request->get('method', $this->container->getParameter('tmd.analyze.default_method'));
            $xmlFile = $file->move(__DIR__ . '/../uploaded', $timeStamp . '_' . $fileName);

            $track = $this->getManager()->create($xmlFile, $method);
            $view = $this->view($track, 200);
            unlink($xmlFile);
        } else {
            $view = $this->view('GPS file is incomplete or not valid!', 400);
        }

        return $this->handleView($view);
    }

    /**
     * Checks if the uploaded file is valid
     *
     * @param UploadedFile $file
     *
     * @return bool
     */
    protected function isValidFile(UploadedFile $file)
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
