<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManager;
use FHV\Bundle\PipesAndFiltersBundle\Component\ComponentInterface;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\Pipe;
use FHV\Bundle\TmdBundle\Entity\Track;
use FHV\Bundle\TmdBundle\Filter\DatabaseWriterInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Manages all operations related to tracks
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
     * @var ComponentInterface
     */
    protected $tpFilter;

    /**
     * @var ComponentInterface
     */
    protected $frFilter;

    /**
     * @var ComponentInterface
     */
    protected $segmentationFilter;

    /**
     * @var ComponentInterface
     */
    protected $segmentFilter;

    /**
     * @var Track
     */
    protected $track;

    /**
     * @var ComponentInterface
     */
    protected $tmFilter;

    /**
     * @var ComponentInterface // TODO remove?
     */
    protected $ppFilter;

    function __construct(
        EntityManager $em,
        ComponentInterface $tpFilter,
        ComponentInterface $fileReaderFilter,
        ComponentInterface $segmentationFilter,
        ComponentInterface $segmentFilter,
        ComponentInterface $travelModeFilter,
        ComponentInterface $postProcessFilter,
        DatabaseWriterInterface $databaseFilterInterface,
        ComponentInterface $gisSegmentFilter
    ) {
        $this->em = $em;
        $this->tpFilter = $tpFilter;
        $this->frFilter = $fileReaderFilter;
        $this->segmentationFilter = $segmentationFilter;
        $this->segmentFilter = $segmentFilter;
        $this->tmFilter = $travelModeFilter;
        $this->ppFilter = $postProcessFilter;
        $this->dbFilter = $databaseFilterInterface;
        $this->gisSegementFilter = $gisSegmentFilter;
        $this->track = new Track();
    }

    /**
     * Creates a track from a gxp file and a for a selected process method
     *
     * @param File    $file
     * @param string $method
     *
     * @return mixed
     */
    public function create(File $file, $method)
    {
        // TODO inject analyse config and check if given mode exists
        $this->track->setAnalyseType($method);
        $this->initFilters($method);
        $this->frFilter->run(['fileName' => $file, 'analyseType' => $method]);
        $this->frFilter->finished();
        $this->em->persist($this->track);
        $this->em->flush();

        return $this->track;
    }

    /**
     * Triggers initialisation of filters
     * @param $method
     * @throws \Exception
     */
    protected function initFilters($method){
        switch($method){
            case 'basic':
                $this->initBasicFilters();
                break;
            case 'gis':
                $this->initGisFilters();
                break;
            default:
                throw new \Exception('Invalid method');
        }
    }

    /**
     * Initializes and connects filters for basic analyse method
     */
    protected function initBasicFilters()
    {
        new Pipe($this->frFilter, $this->tpFilter);
        new Pipe($this->tpFilter, $this->segmentationFilter);
        new Pipe($this->segmentationFilter, $this->segmentFilter);
        new Pipe($this->segmentFilter, $this->tmFilter);
        new Pipe($this->tmFilter, $this->ppFilter);
        new Pipe($this->ppFilter, $this->dbFilter);

//        new Pipe($this->segmentFilter, $this->dbFilter);

        $this->dbFilter->provideTrack($this->track);
    }

    /**
     * Initializes and connects filters for gis analyse method
     */
    protected function initGisFilters()
    {
        new Pipe($this->frFilter, $this->tpFilter);
        new Pipe($this->tpFilter, $this->segmentationFilter);
        new Pipe($this->segmentationFilter, $this->gisSegementFilter);
        new Pipe($this->gisSegementFilter, $this->tmFilter);
        new Pipe($this->tmFilter, $this->ppFilter);
        new Pipe($this->ppFilter, $this->dbFilter);

        $this->dbFilter->provideTrack($this->track);
    }
}
