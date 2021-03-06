<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManager;
use FHV\Bundle\PipesAndFiltersBundle\Component\ComponentInterface;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\Pipe;
use FHV\Bundle\TmdBundle\Entity\Track;
use FHV\Bundle\TmdBundle\Filter\DatabaseWriterInterface;
use InvalidArgumentException;
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
     * @var ComponentInterface
     */
    protected $ppFilter;

    /**
     * @var array
     */
    protected $analyseConfig;

    /**
     * @var boolean
     */
    protected $filtersInitialized;

    function __construct(
        EntityManager $em,
        ComponentInterface $tpFilter,
        ComponentInterface $fileReaderFilter,
        ComponentInterface $segmentationFilter,
        ComponentInterface $segmentFilter,
        ComponentInterface $travelModeFilter,
        ComponentInterface $postProcessFilter,
        DatabaseWriterInterface $databaseFilterInterface,
        ComponentInterface $gisSegmentFilter,
        array $analyseConfig
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
        $this->analyseConfig = $analyseConfig;
    }

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
    public function create(File $file, $analyseType)
    {
        if (!$this->isAnalyseTypeValid($analyseType)) {
            throw new InvalidArgumentException('The given analyse type ('.$analyseType.') seems to be unknown!');
        }

        $this->track = new Track();
        $this->track->setAnalyseType($analyseType);

        if (!$this->filtersInitialized) {
            $this->initFilters($analyseType);
        }

        $this->frFilter->run(['fileName' => $file, 'analyseType' => $analyseType]);
        $this->frFilter->finished();

        $this->em->persist($this->track);
        $this->em->flush();

        return $this->track;
    }

    /**
     * Validates the analyse type against the configuration
     *
     * @param string $analyseType
     *
     * @return bool
     */
    protected function isAnalyseTypeValid($analyseType)
    {
        if (!array_key_exists($analyseType, $this->analyseConfig)) {
            return false;
        }

        return true;
    }

    /**
     * Triggers initialisation of filters
     *
     * @param $method
     *
     * @throws \Exception
     */
    protected function initFilters($method)
    {
        // TODO make analyse methods more configurable
        // TODO connect filters by config and remove this
        switch ($method) {
            case 'basic':
                $this->initBasicFilters();
                break;
            case 'gis':
            case 'gis_without_max':
            case 'gis_without_mean':
            case 'gis_without_rail':
                $this->initGisFilters();
                break;
            default:
                throw new \Exception('Invalid analyse method provided');
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

        $this->dbFilter->provideTrack($this->track);
        $this->filtersInitialized = true;
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
        $this->filtersInitialized = true;
    }
}
