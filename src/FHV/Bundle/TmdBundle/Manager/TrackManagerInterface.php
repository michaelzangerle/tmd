<?php

namespace FHV\Bundle\TmdBundle\Manager;

use FHV\Bundle\TmdBundle\Entity\Track;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Interface for the TrackManager
 *
 * @package FHV\Bundle\TmdBundle\Manager
 */
interface TrackManagerInterface
{
    /**
     * Creates a track from a gxp file and a for a selected process method
     *
     * @param File $file
     * @param string $analyseType
     *
     * @return Track
     *
     * @throws InvalidArgumentException
     */
    public function create(File $file, $analyseType);
}
