<?php

namespace FHV\Bundle\TmdBundle\Controller;

use FHV\Bundle\TmdBundle\Entity\Track;
use FHV\Bundle\TmdBundle\Exception\RestException;
use FHV\Bundle\TmdBundle\Manager\TrackManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Track controller which provides a minimal REST api to create tracks
 * Class TrackController
 * @package FHV\Bundle\TmdBundle\Controller
 */
class TrackController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Processes a post request for a new track
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws RestException
     */
    public function postAction(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        $view = '';

        if (!$this->isValidFile($file)) {
            $exc = new RestException('GPS file is incomplete or not valid!');
            $view = $this->view($exc, 400);
        } else {
            $fileName = $this->createFileName($file);
            $method = $request->get('method', 'basic');
            $xmlFile = $file->move(__DIR__.'/../uploaded', $fileName);

            try {
                $track = $this->getManager()->create($xmlFile, $method);
                $view = $this->view($track, 200);
            } catch (\InvalidArgumentException $ex) {
                $exc = new RestException($ex->getMessage());
                $view = $this->view($exc, 400);
            } finally {
                unlink($xmlFile);
            }
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

    /**
     * Creates a file name for the uploaded file consisting of the original name and a timestamp
     *
     * @param UploadedFile $file
     *
     * @return array
     */
    protected function createFileName(UploadedFile $file)
    {
        $timeStamp = (new \DateTime('now'))->format('U');
        $originalName = preg_replace('/[^a-z0-9._]+/', '', $file->getClientOriginalName());
        $fileName = substr($originalName, 0, 60);

        return $timeStamp.'_'.$fileName;
    }
}
