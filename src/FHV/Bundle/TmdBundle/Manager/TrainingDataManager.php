<?php

namespace FHV\Bundle\TmdBundle\Manager;

use FHV\Bundle\PipesAndFiltersBundle\Filter\FilterInterface;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\Pipe;
use FHV\Bundle\TmdBundle\Filter\FileReaderFilter;
use FHV\Bundle\TmdBundle\Filter\FileWriterFilter;
use FHV\Bundle\TmdBundle\Filter\TracksegmentFilter;
use FHV\Bundle\TmdBundle\Filter\TrackpointFilter;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class GPXTrainingDataManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class TrainingDataManager
{
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
    protected $fwFilter;

    /**
     * @var FilterInterface
     */
    protected $smFilter;

    /**
     * @var array
     */
    protected $analyseConfig;

    /**
     * @param FilterInterface $tpFilter
     * @param FilterInterface $fileReaderFilter
     * @param FilterInterface $fileWriterFilter
     * @param FilterInterface $segmentFilter
     * @param array $analyseConfig
     */
    function __construct(
        FilterInterface $tpFilter,
        FilterInterface $fileReaderFilter,
        FilterInterface $fileWriterFilter,
        FilterInterface $segmentFilter,
        array $analyseConfig
    )
    {
        $this->tpFilter = $tpFilter;
        $this->frFilter = $fileReaderFilter;
        $this->fwFilter = $fileWriterFilter;
        $this->smFilter = $segmentFilter;
        $this->analyseConfig = $analyseConfig;
        // TODO add filter for gis
    }

    /**
     * Reads gpx files from directory, processes them and generates a file with the result
     *
     * @param OutputInterface $output
     * @param string $dir
     * @param string $resultFileName
     * @param string $analyseType
     */
    public function process($output, $dir = '.', $resultFileName = 'results.csv', $analyseType = 'basic')
    {
        if (!is_dir($dir)) {
            throw new FileNotFoundException('The directory ' . $dir . ' does not exist!');
        }

        if (!$this->isAnalyzeTypeValid($analyseType)) {
            throw new \InvalidArgumentException('The given analyse type ('.$analyseType.') seems to be unknown!');
        }

        $files = glob($dir . '/*.gpx');
        if (count($files) > 0) {
            $this->connectFilters();
            $this->fwFilter->setFilePath($dir . $resultFileName);

            foreach ($files as $fileName) {
                $output->write('<info>Processing "' . $fileName . '"</info>', true);
                $this->frFilter->run(
                    [
                        'fileName' => $fileName,
                        'analyseType' => $analyseType
                    ]
                );
            }
            $this->frFilter->parentHasFinished();
            $output->write(PHP_EOL . '<info>' . count($files) . ' gpx files found and processed!</info>' . PHP_EOL);
        } else {
            $output->write('<info>No gpx files found!</info>' . PHP_EOL);
        }
    }

    /**
     * Connect filters with pipes
     */
    protected function connectFilters()
    {
        // TODO add analyze type argument and connect differently

        new Pipe($this->frFilter, $this->tpFilter);
        new Pipe($this->tpFilter, $this->smFilter);
        new Pipe($this->smFilter, $this->fwFilter);
    }

    /**
     * Validates the given analyze type
     * @param string $analyzeType
     * @return boolean
     */
    protected function isAnalyzeTypeValid($analyzeType)
    {
        foreach ($this->analyseConfig as $key => $type) {
            if ($key === $analyzeType) {
                return true;
            }
        }
        return false;
    }
}
