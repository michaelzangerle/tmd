<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Model\SegmentInterface;
use SimpleXMLElement;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Reads a file and passes its contents on
 * Class FileReaderFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class FileReaderFilter extends AbstractFilter
{
    /**
     * @var int
     */
    protected $minTrackPoints;

    /**
     * @var string
     */
    protected $gpxNameSpace;

    function __construct($minTrackPoints, $gpxNameSpace)
    {
        $this->minTrackPoints = $minTrackPoints;
        $this->gpxNameSpace = $gpxNameSpace;
    }

    /**
     * Starts a filter and processes the given data
     * @param $data
     * @throws FilterException
     */
    public function run($data)
    {
        if ($data !== null && is_file($data)) {
            $this->readSegments($data);
        } else {
            throw new InvalidArgumentException('FileReaderFilter: Parameter should be a valid file name!');
        }
    }

    /**
     * Reads all segments from a gpx file
     * @param string $fileName
     */
    protected function readSegments($fileName)
    {
        $doc = new \SimpleXMLElement($fileName, 0, true);
        $doc->registerXPathNamespace('gpx', $this->gpxNameSpace);
        $segments = $doc->xpath('//gpx:trkseg');

        foreach ($segments as $segment) {
            $this->processSegment($segment);
        }
    }

    /**
     * Processes all points of a track segment and returns a segment model
     * @param SimpleXmlElement $xmlSegment
     * @return SegmentInterface
     */
    private function processSegment($xmlSegment)
    {
        $type = (string)$xmlSegment['type'];
        $segment = (array)$xmlSegment;
        $trackPoints = (array)$segment['trkpt'];

        if (count($trackPoints) >= $this->minTrackPoints) {
            $this->write(
                array(
                    'type' => $type,
                    'segment' => $segment,
                    'trackPoints' => $trackPoints
                )
            );
        }
    }
}
