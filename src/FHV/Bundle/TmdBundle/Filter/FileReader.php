<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Component\AbstractComponent;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\ComponentException;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Model\TracksegmentInterface;
use SimpleXMLElement;

/**
 * Reads a file and passes its contents on to the connected filters
 * Class FileReader
 * @package FHV\Bundle\TmdBundle\Filter
 */
class FileReader extends AbstractComponent
{
    /**
     * @var int
     */
    protected $minTrackPoints;

    /**
     * @var string
     */
    protected $gpxNameSpace;

    /**
     * @var string
     */
    protected $analyseType;

    function __construct($minTrackPoints, $gpxNameSpace)
    {
        parent::__construct();
        $this->minTrackPoints = $minTrackPoints;
        $this->gpxNameSpace = $gpxNameSpace;
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
            array_key_exists('fileName', $data) && is_file($data['fileName']) &&
            array_key_exists('analyseType', $data) && $data['analyseType'] !== null
        ) {
            $this->analyseType = $data['analyseType'];
            $this->readSegments($data['fileName']);
        } else {
            throw new InvalidArgumentException(
                'FileReaderFilter: Data should contain a valid file name and a analyse type!'
            );
        }
    }

    /**
     * Reads all segments from a gpx file
     *
     * @param string $fileName
     *
     * @throws InvalidArgumentException
     */
    protected function readSegments($fileName)
    {
        $doc = new \SimpleXMLElement($fileName, 0, true);
        $ns = $this->getNamespace($doc->getNamespaces());
        $doc->registerXPathNamespace('gpx', $ns);
        $segments = $doc->xpath('//gpx:trkseg');

        if (count($segments) === 0) {
            throw new InvalidArgumentException('FileReaderFilter: No segments found!');
        }

        foreach ($segments as $segment) {
            $this->processSegment($segment);
        }
    }

    /**
     * Processes all points of a track segment and returns a segment model
     *
     * @param SimpleXmlElement $xmlSegment
     *
     * @return TracksegmentInterface
     */
    protected function processSegment($xmlSegment)
    {
        $type = (string)$xmlSegment['type'];
        $segment = (array)$xmlSegment;
        $trackPoints = (array)$segment['trkpt'];

        if (count($trackPoints) >= $this->minTrackPoints) {
            $this->write(
                array(
                    'analyseType' => $this->analyseType,
                    'type' => $type,
                    'trackPoints' => $trackPoints,
                )
            );
        }
    }

    /**
     * Returns first namespace with containing gpx or default
     *
     * @param array $namespaces
     *
     * @return string
     */
    protected function getNamespace(array $namespaces)
    {
        $ns = $this->gpxNameSpace;
        foreach ($namespaces as $namespace) {
            $tmpNs = strtolower($namespace);
            if (strpos($tmpNs, 'gpx') !== false) {
                $ns = $namespace;
                break;
            }
        }

        return $ns;
    }
}
