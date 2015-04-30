<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManager;
use FHV\Bundle\PipesAndFiltersBundle\Filter\FilterInterface;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\Pipe;
use FHV\Bundle\TmdBundle\Entity\Track;
use FHV\Bundle\TmdBundle\Entity\Tracksegment;
use FHV\Bundle\TmdBundle\Filter\FileReaderFilter;
use FHV\Bundle\TmdBundle\Filter\SegmentationFilter;
use FHV\Bundle\TmdBundle\Filter\SegmentationFilterInterface;
use FHV\Bundle\TmdBundle\Filter\TrackpointFilter;
use FHV\Bundle\TmdBundle\Filter\TracksegmentFilter;
use FHV\Bundle\TmdBundle\Model\Trackpoint;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class TrackManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class TrackManager implements TrackManagerInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FilterInterface
     */
    protected $tpFilter;

    /**
     * @var FilterInterface
     */
    protected $frFilter;

    /**
     * @var SegmentationFilterInterface
     */
    protected $segmentationFilter;

    /**
     * @var FilterInterface
     */
    protected $segmentFilter;

    /**
     * @var Track
     */
    private $track;

    function __construct(
        EntityManager $em,
        FilterInterface $tpFilter,
        FilterInterface $fileReaderFilter,
        SegmentationFilterInterface $segmentationFilter,
        FilterInterface $segmentFilter
    ) {
        $this->em = $em;
        $this->tpFilter = $tpFilter;
        $this->frFilter = $fileReaderFilter;
        $this->segmentationFilter = $segmentationFilter;
        $this->segmentFilter = $segmentFilter;
        $this->track = new Track();
    }

    /**
     * Creates a track from a gxp file and a for a selected process method
     *
     * @param File    $file
     * @param integer $method
     *
     * @return mixed
     */
    public function create(File $file, $method)
    {
        $this->track->setAnalyzationType($this->getType($method));
        $this->initFilters();
        $this->frFilter->run($file);
        $this->em->persist($this->track);
        $this->em->flush();

        return $this->track;
    }

    /**
     * Returns the analyzation method type
     *
     * @param $method
     *
     * @return int
     */
    protected function getType($method)
    {
        switch ($method) {
            case Track::TYPE_BASIC:
            case Track::TYPE_GIS:
                return $method;
            default:
                return Track::TYPE_UNKNOW;
        }
    }

    /**
     * Initializes and connects filters
     */
    protected function initFilters()
    {
        $this->segmentationFilter->setTrack($this->track);

        new Pipe($this->frFilter, $this->tpFilter);
        new Pipe($this->tpFilter, $this->segmentFilter);
        new Pipe($this->segmentFilter, $this->segmentationFilter);
    }
}
