<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\TmdBundle\Model\TracksegmentInterface;

/**
 * Writes segments as csv into a file
 * Class FileWriterFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class FileWriterFilter extends AbstractFilter
{
    /**
     * @var string
     */
    protected $analyseType = 'basic';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $filePath = 'results.csv';

    /**
     * @var string
     */
    protected $delimiter = ';';

    /**
     * @var bool
     */
    protected $headerWritten = false;

    /**
     * Array to collect data to write
     * @var TracksegmentInterface[]
     */
    protected $data = [];

    function __construct($analyzeConfiguration)
    {
        parent::__construct();
        $this->config = $analyzeConfiguration;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @return string
     */
    public function getAnalyseType()
    {
        return $this->analyseType;
    }

    /**
     * @param string $analyseType
     */
    public function setAnalyseType($analyseType)
    {
        $this->analyseType = $analyseType;
    }

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws FilterException
     */
    public function run($data)
    {
        if ($data !== null &&
            array_key_exists('segment', $data) && $data['segment'] instanceof TracksegmentInterface &&
            array_key_exists('analyseType', $data) && $data['analyseType'] !== null
        ) {
            $this->data[] = $data['segment'];
        }
    }

    /**
     * Writes the csv result file for all analyzed segments
     *
     * @param                       $fp
     * @param array                 $values
     * @param string                $delimiter
     */
    protected function writeData($fp, array $values, $delimiter)
    {
        fputcsv($fp, $values, $delimiter);
    }

    /**
     * Returns csv file headers depending on the given analyse type
     *
     * @param string $analyseType
     *
     * @return array
     */
    public function getCSVFeatureKeys($analyseType = 'basic')
    {
        return $this->config[$analyseType]['csv_fileHeaders'];
    }

    /**
     * @param TracksegmentInterface $seg
     *
     * @param string                $analyseType
     *
     * @return array
     */
    public function getCSVFeatureValues(TracksegmentInterface $seg, $analyseType = 'basic')
    {
        $fields = $this->config[$analyseType]['csv_fileHeaders'];
        $result = [];
        foreach ($fields as $field) {
            $result[$field] = $seg->getFeature($field);
        }

        return $result;
    }

    /**
     * Writes the header of a csv file
     *
     * @param        $fp
     * @param        $keys
     * @param string $delimiter
     */
    protected function writeHeader($fp, $keys, $delimiter)
    {
        fputcsv($fp, $keys, $delimiter);
    }

    /**
     * Will be called when the parent filter has finished work
     */
    public function parentHasFinished()
    {
        $fp = fopen($this->filePath, 'a+');
        flock($fp, LOCK_EX);
        // for multiple files
        if (!$this->headerWritten) {
            $this->headerWritten = true;
            $this->writeHeader($fp, $this->getCSVFeatureKeys($this->analyseType), $this->delimiter);
        }

        foreach ($this->data as $seg) {
            $this->writeData($fp, $this->getCSVFeatureValues($seg, $this->analyseType), $this->delimiter);
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        $this->data = [];
        $this->finished();
    }
}
