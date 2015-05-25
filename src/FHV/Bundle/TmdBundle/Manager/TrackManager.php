<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManager;
use FHV\Bundle\PipesAndFiltersBundle\Filter\FilterInterface;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\Pipe;
use FHV\Bundle\TmdBundle\DecisionTree\Manager\DecisionTreeManager;
use FHV\Bundle\TmdBundle\Entity\Track;
use FHV\Bundle\TmdBundle\Filter\DatabaseFilterInterface;
use Symfony\Component\HttpFoundation\File\File;

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
     * @var FilterInterface
     */
    protected $segmentationFilter;

    /**
     * @var FilterInterface
     */
    protected $segmentFilter;

    /**
     * @var Track
     */
    protected $track;

    /**
     * @var FilterInterface
     */
    protected $tmFilter;

    /**
     * @var FilterInterface // TODO remove?
     */
    protected $ppFilter;

    function __construct(
        EntityManager $em,
        FilterInterface $tpFilter,
        FilterInterface $fileReaderFilter,
        FilterInterface $segmentationFilter,
        FilterInterface $segmentFilter,
        FilterInterface $travelModeFilter,
        FilterInterface $postProcessFilter,
        DatabaseFilterInterface $databaseFilterInterface
    ) {
        $this->em = $em;
        $this->tpFilter = $tpFilter;
        $this->frFilter = $fileReaderFilter;
        $this->segmentationFilter = $segmentationFilter;
        $this->segmentFilter = $segmentFilter;
        $this->tmFilter = $travelModeFilter;
        $this->ppFilter = $postProcessFilter;
        $this->dbFilter = $databaseFilterInterface;
        $this->track = new Track();

        // TODO add gis filter
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
        // TODO inject analyse config and check if given mode exists
        $this->track->setAnalyseType($method);
        $this->initBasicFilters(); // TODO add analyze type argument
        $this->frFilter->run(['fileName' => $file, 'analyseType' => $method]);
        $this->frFilter->parentHasFinished();
        $this->em->persist($this->track);
        $this->em->flush();

        return $this->track;
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

        $this->dbFilter->provideTrack($this->track);
    }
}
