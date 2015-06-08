<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Component\AbstractComponent;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\ComponentException;
use FHV\Bundle\TmdBundle\Model\TracksegmentInterface;

/**
 * Writes segments as csv into a file
 * Class FileWriter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class FileWriter extends AbstractComponent implements FileWriterInterface
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

    function __construct($analyseConfiguration)
    {
        parent::__construct();
        $this->config = $analyseConfiguration;
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
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws ComponentException
     */
    public function run($data)
    {
        if ($data !== null &&
            array_key_exists('segment', $data) && $data['segment'] instanceof TracksegmentInterface &&
            array_key_exists('analyseType', $data) && $data['analyseType'] !== null
        ) {
            $this->data[] = $data['segment'];
            $this->analyseType = $data['analyseType'];
        }
    }

    /**
     * Writes the csv result file for all analyzed segments
     *
     * @param $fp
     * @param array $values
     * @param string $delimiter
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
    protected function getCSVFeatureKeys($analyseType = 'basic')
    {
        return $this->config[$analyseType]['csv_columns'];
    }

    /**
     * @param TracksegmentInterface $seg
     *
     * @param string $analyseType
     *
     * @return array
     */
    protected function getCSVFeatureValues(TracksegmentInterface $seg, $analyseType = 'basic')
    {
        $fields = $this->config[$analyseType]['csv_columns'];
        $result = [];
        foreach ($fields as $field) {
            $result[$field] = $seg->getFeature($field);
        }

        $result[] = $seg->getType();

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
        $keys[] = 'type';
        fputcsv($fp, $keys, $delimiter);
    }

    /**
     * {@inheritdoc}
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
