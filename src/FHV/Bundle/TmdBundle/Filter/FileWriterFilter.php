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
     * @var array
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
        if ($data !== null) {
            $this->data[] = $data;
        }
    }

    /**
     * Writes the csv result file for all analyzed segments
     *
     * @param                       $fp
     * @param TracksegmentInterface $seg
     * @param string                $delimiter
     */
    protected function writeData($fp, TracksegmentInterface $seg, $delimiter)
    {
        fputcsv($fp, $seg->toCSVArray(), $delimiter);
    }

    /**
     * Writes the header of a csv file
     *
     * @param        $fp
     * @param string $delimiter
     */
    protected function writeHeader($fp, $delimiter)
    {
        fputcsv($fp, Tracksegment::$ATTRIBUTES, $delimiter);
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
            $this->writeHeader($fp, $this->delimiter);

        }

        foreach ($this->data as $values) {
            $this->writeData($fp, $values, $this->delimiter);
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        $this->data = [];
        $this->finished();
    }
}
