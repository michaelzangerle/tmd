<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AnalyseManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class AnalyseManager implements AnalyseMangerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityName = 'FHVTmdBundle:Result';

    /**
     * @var array
     */
    protected $config;

    function __construct($em, $config)
    {
        $this->em = $em;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getOverview()
    {
        $response = [];
        foreach ($this->config as $analyseType => $config) {
            $response[$analyseType] = [];
            $response[$analyseType]['total'] = $this->getResultsCountBy(
                [
                    'analyseType' => $analyseType
                ]
            );
            $response[$analyseType]['correctIdentified'] = $this->getResultsCountBy(
                ['analyseType' => $analyseType, 'correctedTransportType' => null]
            );
        }

        return $response;
    }

    /**
     * Gets results by criteria
     *
     * @param array $criteria
     *
     * @return int
     */
    protected function getResultsCountBy(array $criteria)
    {
        return $this->em->getRepository($this->entityName)->findByAndCount($criteria);
    }

    /**
     * Gets results by criteria
     *
     * @param string  $analyseType
     * @param string  $mode
     *
     * @param boolean $correctedTransportType
     *
     * @return int
     */
    protected function getResultsCountByMode($analyseType, $mode, $correctedTransportType = null)
    {
        $criteria = [
            'analyseType' => $analyseType,
            'transportType' => $mode,
            'correctedTransportType' => $correctedTransportType
        ];

        return $this->getResultsCountBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function getDetail($mode = null)
    {
        if (is_null($mode)) {
            return $this->getDetailPerMode();
        } else {
            $response = [];
            foreach ($this->config as $analyseType => $config) {
                $response[$analyseType] = [];
                $response[$analyseType] = [];
                $response[$analyseType]['total'] = $this->getResultsCountByMode($analyseType, $mode);

                // todo make modes configurable?
                $response[$analyseType]['bus'] = $this->getResultsCountByMode($analyseType, $mode, 'bus');
                $response[$analyseType]['drive'] = $this->getResultsCountByMode($analyseType, $mode, 'drive');
                $response[$analyseType]['bike'] = $this->getResultsCountByMode($analyseType, $mode, 'bike');
                $response[$analyseType]['walk'] = $this->getResultsCountByMode($analyseType, $mode, 'walk');
                $response[$analyseType]['train'] = $this->getResultsCountByMode($analyseType, $mode, 'train');
            }

            return $response;
        }
    }

    /**
     * Gets the per analyse type and per mode the total and correct identified segments
     *
     *
     * @return array
     */
    protected function getDetailPerMode()
    {
        $response = [];
        foreach ($this->config as $analyseType => $config) {
            $response[$analyseType] = [];
            $response[$analyseType]['total'] = $this->getResultsCountBy(['analyseType' => $analyseType]);

            // todo make modes configurable?
            $response[$analyseType]['bus'] = $this->getResultsCountByMode($analyseType, 'bus', null);
            $response[$analyseType]['drive'] = $this->getResultsCountByMode($analyseType, 'drive', null);
            $response[$analyseType]['bike'] = $this->getResultsCountByMode($analyseType, 'bike', null);
            $response[$analyseType]['walk'] = $this->getResultsCountByMode($analyseType, 'walk', null);
            $response[$analyseType]['train'] = $this->getResultsCountByMode($analyseType, 'train', null);
        }

        return $response;
    }
}
