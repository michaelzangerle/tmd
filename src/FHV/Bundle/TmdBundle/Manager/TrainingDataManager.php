<?php

namespace FHV\Bundle\TmdBundle\Manager;

use FHV\Bundle\PipesAndFiltersBundle\Component\ComponentInterface;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\Pipe;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Manages all operations for training data
 * Class GPXTrainingDataManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class TrainingDataManager
{
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
    protected $fileWriter;

    /**
     * @var ComponentInterface
     */
    protected $smFilter;

    /**
     * @var array
     */
    protected $analyseConfig;

    /**
     * @var ComponentInterface
     */
    protected $gisSegmentFilter;

    /**
     * @param ComponentInterface $tpFilter
     * @param ComponentInterface $fileReaderFilter
     * @param ComponentInterface $fileWriterFilter
     * @param ComponentInterface $segmentFilter
     * @param ComponentInterface $gisSegmentFilter
     * @param array $analyseConfig
     */
    function __construct(
        ComponentInterface $tpFilter,
        ComponentInterface $fileReaderFilter,
        ComponentInterface $fileWriterFilter,
        ComponentInterface $segmentFilter,
        ComponentInterface $gisSegmentFilter,
        array $analyseConfig
    ) {
        $this->tpFilter = $tpFilter;
        $this->frFilter = $fileReaderFilter;
        $this->fileWriter = $fileWriterFilter;
        $this->smFilter = $segmentFilter;
        $this->analyseConfig = $analyseConfig;
        $this->gisSegmentFilter = $gisSegmentFilter;
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
            throw new FileNotFoundException('The directory '.$dir.' does not exist!');
        }

        if (!$this->isAnalyzeTypeValid($analyseType)) {
            throw new \InvalidArgumentException('The given analyse type ('.$analyseType.') seems to be unknown!');
        }

        $files = glob($dir.'/*.gpx');
        if (count($files) > 0) {
            $this->connectFilters($analyseType);
            $this->fileWriter->setFilePath($dir.$resultFileName);

            foreach ($files as $fileName) {
                $output->write('<info>Processing "'.$fileName.'"</info>', true);
                $this->frFilter->run(
                    [
                        'fileName' => $fileName,
                        'analyseType' => $analyseType,
                    ]
                );
            }
            $this->frFilter->finished();
            $output->write(PHP_EOL.'<info>'.count($files).' gpx files found and processed!</info>'.PHP_EOL);
        } else {
            $output->write('<info>No gpx files found!</info>'.PHP_EOL);
        }
    }

    /**
     * Connect filters with pipes depending on the analyse type
     *
     * @param string $analyseType
     */
    protected function connectFilters($analyseType)
    {
        if ($analyseType === 'gis') {
            new Pipe($this->frFilter, $this->tpFilter);
            new Pipe($this->tpFilter, $this->gisSegmentFilter);
            new Pipe($this->gisSegmentFilter, $this->fileWriter);
        } else {
            new Pipe($this->frFilter, $this->tpFilter);
            new Pipe($this->tpFilter, $this->smFilter);
            new Pipe($this->smFilter, $this->fileWriter);
        }
    }

    /**
     * Validates the given analyze type
     *
     * @param string $analyzeType
     *
     * @return boolean
     */
    protected function isAnalyzeTypeValid($analyzeType)
    {
        if (array_key_exists($analyzeType, $this->analyseConfig)) {

            return true;
        }

        return false;
    }
}
