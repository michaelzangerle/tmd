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
            $this->writeHeader($this->dir, $this->fileName, $this->delimiter);
            $this->writeData($this->dir, $this->fileName, $data, $this->delimiter);
        }

        if ($this->getParentHasFinished()) {
            $this->finished();
        }
    }

    /**
     * Writes the csv result file for all analyzed segments
     *
     * @param string                $dir
     * @param                       $fileName
     * @param TracksegmentInterface $seg
     * @param string                $delimiter
     */
    protected function writeData($dir, $fileName, TracksegmentInterface $seg, $delimiter)
    {
        $fp = fopen($dir . $fileName, 'a+');
        fputcsv($fp, $seg->toCSVArray(), $delimiter);
        fclose($fp);
    }

    /**
     * Writes the header of a csv file
     *
     * @param        $dir
     * @param        $fileName
     * @param string $delimiter
     */
    protected function writeHeader($dir, $fileName, $delimiter)
    {
        if (!$this->headerWritten) {
            $this->headerWritten = true;
            $fp = fopen($dir . $fileName, 'w');
            fputcsv($fp, Tracksegment::$ATTRIBUTES, $delimiter);
            fclose($fp);
        }
    }
}
