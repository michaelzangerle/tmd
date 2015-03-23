<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\TmdBundle\Model\Segment;
use FHV\Bundle\TmdBundle\Model\SegmentInterface;

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
    private $dir = '../gpx/';

    /**
     * @var string
     */
    private $fileName = 'results.csv';

    /**
     * @var string
     */
    private $delimiter = ';';

    /**
     * @var bool
     */
    private $headerWritten = false;

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
        if(substr($dir, -1) !== '/'){
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
     * @param $data
     * @param $log
     * @throws FilterException
     */
    public function run($data, $log)
    {
        if ($data !== null) {
            $this->writeHeader($this->dir, $this->fileName, $this->delimiter);
            $this->writeData($this->dir, $this->fileName, $data, $this->delimiter);
        }
    }

    /**
     * Writes the csv result file for all analyzed segments
     * @param string $dir
     * @param $fileName
     * @param SegmentInterface $seg
     * @param string $delimiter
     */
    protected function writeData($dir, $fileName, SegmentInterface $seg, $delimiter)
    {
        $fp = fopen($dir . $fileName, 'a+');
        fputcsv($fp, $seg->toCSVArray(), $delimiter);
        fclose($fp);
    }

    /**
     * Writes the header of a csv file
     * @param $dir
     * @param $fileName
     * @param string $delimiter
     */
    protected function writeHeader($dir, $fileName, $delimiter)
    {
        if (!$this->headerWritten) {
            $this->headerWritten = true;
            $fp = fopen($dir . $fileName, 'w');
            fputcsv($fp, Segment::$ATTRIBUTES, $delimiter);
            fclose($fp);
        }
    }
}
