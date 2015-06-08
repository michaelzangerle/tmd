<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Manages all operations for analyses
 * Class AnalyseManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class AnalyseManager implements AnalyseMangerInterface
{
    /**
     * Transportation modes
     *
     * @var array
     */
    protected $transportationModes;

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
    protected $analyseConfig;

    function __construct($em, $config, $transportationModes)
    {
        $this->em = $em;
        $this->analyseConfig = $config;
        $this->transportationModes = $transportationModes;
    }

    /**
     * {@inheritdoc}
     */
    public function getOverview()
    {
        $response = [];
        foreach ($this->analyseConfig as $analyseType => $config) {
            $response[$analyseType] = [];
            $response[$analyseType]['total'] = $this->getResultsCountBy(
                [
                    'analyseType' => $analyseType,
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
     * @param string $analyseType
     * @param string $mode
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
            'correctedTransportType' => $correctedTransportType,
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
            foreach ($this->analyseConfig as $analyseType => $config) {
                $response[$analyseType] = [];
                $response[$analyseType]['total'] = $this->getResultsCountByMode($analyseType, $mode);

                foreach ($this->transportationModes as $m) {
                    if ($m !== $mode) {
                        $response[$analyseType][$m] = $this->getResultsCountByMode($analyseType, $m, $mode);
                    }
                }
            }

            return $response;
        }
    }

    /**
     * Gets the per analyse type and per mode the total and correct identified segments
     *
     * @return array
     */
    protected function getDetailPerMode()
    {
        $response = [];
        foreach ($this->analyseConfig as $analyseType => $config) {
            $response[$analyseType] = [];
            $response[$analyseType]['total'] = $this->getResultsCountBy(['analyseType' => $analyseType]);

            foreach ($this->transportationModes as $mode) {
                $response[$analyseType][$mode] = $this->getResultsCountByMode($analyseType, $mode);
            }
        }

        return $response;
    }
}
