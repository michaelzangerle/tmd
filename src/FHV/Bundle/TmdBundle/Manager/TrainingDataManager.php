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

// TODO use interface

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
     * @param FilterInterface $tpFilter
     * @param FilterInterface $fileReaderFilter
     * @param FilterInterface $fileWriterFilter
     * @param FilterInterface $segmentFilter
     */
    function __construct(
        FilterInterface $tpFilter,
        FilterInterface $fileReaderFilter,
        FilterInterface $fileWriterFilter,
        FilterInterface $segmentFilter
    ) {
        $this->tpFilter = $tpFilter;
        $this->frFilter = $fileReaderFilter;
        $this->fwFilter = $fileWriterFilter;
        $this->smFilter = $segmentFilter;
    }

    /**
     * Reads gpx files from directory, processes them and generates a file with the result
     * @param OutputInterface $output
     * @param string $dir
     * @param $resultFileName
     */
    public function process($output, $dir = '.', $resultFileName = 'results.csv')
    {
        if (is_dir($dir)) {
            $files = glob($dir . '/*.gpx');
            if (count($files) > 0) {

                $this->connectFilters();
                $this->fwFilter->setDir($dir);
                $this->fwFilter->setFileName($resultFileName);

                foreach ($files as $fileName) {
                    $output->write('<info>Processing "' . $fileName . '"</info>', true);
                    $this->frFilter->run($fileName);
                }
                $output->write(PHP_EOL . '<info>' . count($files) . ' gpx files found and processed!</info>' . PHP_EOL);
            } else {
                $output->write('<info>No gpx files found!</info>' . PHP_EOL);
            }
        } else {
            throw new FileNotFoundException('The directory ' . $dir . ' does not exist!');
        }
    }

    /**
     * Connect filters with pipes
     */
    protected function connectFilters()
    {
        new Pipe($this->frFilter, $this->tpFilter);
        new Pipe($this->tpFilter, $this->smFilter);
        new Pipe($this->smFilter, $this->fwFilter);
    }
}
