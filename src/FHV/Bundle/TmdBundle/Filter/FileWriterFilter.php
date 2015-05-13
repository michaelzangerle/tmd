<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\TmdBundle\Model\Tracksegment;
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
    protected $dir = '../gpx/';

    /**
     * @var string
     */
    protected $fileName = 'results.csv';

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

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param string $dir
     */
    public function setDir($dir)
    {
        if (substr($dir, -1) !== '/') {
            $dir .= '/';
        }

        $this->dir = $dir;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
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
     * @throws FilterException
     */
    public function run($data)
    {
        if ($data !== null && $data instanceof TracksegmentInterface) {
            $this->data[] = $data;
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
     * @return array
     */
    public function getCSVFeatureKeys()
    {
        return [
            'stoprate',
            'meanVelocity',
            'meanAcceleration',
            'maxVelocity',
            'maxAcceleration',
            'type'
        ];
    }

    /**
     * @param TracksegmentInterface $seg
     *
     * @return array
     */
    public function getCSVFeatureValues(TracksegmentInterface $seg)
    {
        return [
            'stopRate' => $seg->getFeature('stopRate'),
            'meanVelocity' => $seg->getFeature('meanVelocity'),
            'meanAcceleration' => $seg->getFeature('meanAcceleration'),
            'maxVelocity' => $seg->getFeature('maxVelocity'),
            'maxAcceleration' => $seg->getFeature('maxAcceleration'),
            'type' => $seg->getTypeAsString()
        ];
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
        $fp = fopen($this->dir . $this->fileName, 'a+');
        flock($fp, LOCK_EX);
        // for multiple files
        if (!$this->headerWritten) {
            $this->headerWritten = true;
            $this->writeHeader($fp, $this->getCSVFeatureKeys(), $this->delimiter);
        }

        foreach ($this->data as $seg) {
            $this->writeData($fp, $this->getCSVFeatureValues($seg), $this->delimiter);
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        $this->data = [];
        $this->finished();
    }
}
