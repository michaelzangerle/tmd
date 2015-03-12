<?php

namespace FHV\Bundle\TmdBundle\Manager;

use FHV\Bundle\TmdBundle\Model\Segment;
use SimpleXMLElement;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class GPXTrainingDataManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class GPXTrainingDataManager
{
    /**
     * @var SegmentManager
     */
    protected $sm;

    /**
     * for each file get segments
     * let segments be analyzed by segmentreader
     * write segments into file depending on format
     */

    /**
     * @param SegmentManager $sm
     */
    function __construct(SegmentManager $sm)
    {
        $this->sm = $sm;
    }

    /**
     * Reads gpx files from directory, processes them and generates a file with the result
     * @param string $dir
     * @param string $format
     * @param OutputInterface $output
     */
    public function process($dir = '.', $format = 'csv', $output)
    {
        $segments = [];
        if (file_exists($dir)) {
            $files = glob($dir . '/*.gpx');
            if (count($files) > 0) {
                foreach ($files as $fileName) {
                    $output->write('<info>Processing "' . $fileName . '"</info>', true);
                    $segments = array_merge($segments, $this->readSegments($fileName, $output));
                    $output->write(PHP_EOL, true);
                }
                $output->write(PHP_EOL . '<info>' . count($files) . ' gpx files found and processed!</info>' . PHP_EOL);
            } else {
                $output->write('<info>No gpx files found!</info>' . PHP_EOL);
            }

            // TODO write result

        } else {
            throw new FileNotFoundException('The directory ' . $dir . ' does not be exist!');
        }
    }

    /**
     * Reads all segments from a gpx file
     * @param string $fileName
     * @param OutputInterface $output
     * @return array
     */
    protected function readSegments($fileName, $output)
    {
        $result = [];
        $doc = new \SimpleXMLElement($fileName, 0, true);
        $doc->registerXPathNamespace('gpx', 'http://www.topografix.com/GPX/1/1');
        $segments = $doc->xpath('//gpx:trkseg/.');
        $pb = new ProgressBar($output, count($segments));
        $pb->start();
        $i = 0;
        while ($i++ < count($segments)) {
            foreach ($segments as $segment) {
                $result[] = $this->processSegment($segment);
                $pb->advance();
            }
        }
        $pb->finish();

        return $result;
    }

    /**
     * Processes all points of a track segment and returns a segment model
     * @param SimpleXmlElement $xmlSegment
     * @return Segment
     */
    private function processSegment($xmlSegment)
    {
        $type = (string)$xmlSegment['type'];
        $segment = (array) $xmlSegment;
        $trackPoints = (array) $segment['trkpt'];

        $this->sm->createSegment($trackPoints, $type);

        return null;
    }
}
