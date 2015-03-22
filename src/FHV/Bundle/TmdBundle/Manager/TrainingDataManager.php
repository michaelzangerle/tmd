<?php

namespace FHV\Bundle\TmdBundle\Manager;

use FHV\Bundle\PipesAndFiltersBundle\Filter\FilterInterface;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\Pipe;
use FHV\Bundle\TmdBundle\Filter\FileReaderFilter;
use FHV\Bundle\TmdBundle\Filter\FileWriterFilter;
use FHV\Bundle\TmdBundle\Filter\SegmentFilter;
use FHV\Bundle\TmdBundle\Filter\TrackPointFilter;
use FHV\Bundle\TmdBundle\Model\SegmentInterface;
use FHV\Bundle\TmdBundle\Model\Segment;
use SimpleXMLElement;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class GPXTrainingDataManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class TrainingDataManager
{
    /**
     * @var SegmentManager
     */
    protected $sm;

    /**
     * @var FilterInterface
     */
    protected $tpFilter;

    /**
     * @var FilterInterface
     */
    protected $frFilter;

    /**
     * @var FilterInterface
     */
    protected $fwFilter;

    /**
     * @var FilterInterface
     */
    protected $smFilter;

    /**
     * @param SegmentManager $sm
     * @param TrackPointFilter $tpFilter
     * @param FileReaderFilter $fileReaderFilter
     * @param FileWriterFilter $fileWriterFilter
     * @param SegmentFilter $segmentFilter
     */
    function __construct(
        SegmentManager $sm,
        TrackPointFilter $tpFilter,
        FileReaderFilter $fileReaderFilter,
        FileWriterFilter $fileWriterFilter,
        SegmentFilter $segmentFilter
    )
    {
        $this->sm = $sm;
        $this->tpFilter = $tpFilter;
        $this->frFilter = $fileReaderFilter;
        $this->fwFilter = $fileWriterFilter;
        $this->smFilter = $segmentFilter;
    }

    /**
     * Reads gpx files from directory, processes them and generates a file with the result
     * @param string $dir
     * @param OutputInterface $output
     */
    public function process($dir = '.', $output)
    {
        $segments = [];
        if (file_exists($dir)) {
            $files = glob($dir . '/*.gpx');
            if (count($files) > 0) {

                $this->connectFilters();

                foreach ($files as $fileName) {
                    $output->write('<info>Processing "' . $fileName . '"</info>', true);

                    // todo
                    // file --> filter
                    // filter  --> segment
                    // segment  --> file
                    $this->frFilter->run($fileName);



                    $output->write(PHP_EOL, true);
                }
                $output->write(PHP_EOL . '<info>' . count($files) . ' gpx files found and processed!</info>' . PHP_EOL);
            } else {
                $output->write('<info>No gpx files found!</info>' . PHP_EOL);
            }
//            $this->writeFile($dir, $segments);
        } else {
            throw new FileNotFoundException('The directory ' . $dir . ' does not be exist!');
        }
    }

//    /**
//     * Reads gpx files from directory, processes them and generates a file with the result
//     * @param string $dir
//     * @param OutputInterface $output
//     */
//    public function process($dir = '.', $output)
//    {
//        // todo
//
//        $segments = [];
//        if (file_exists($dir)) {
//            $files = glob($dir . '/*.gpx');
//            if (count($files) > 0) {
//                foreach ($files as $fileName) {
//                    $output->write('<info>Processing "' . $fileName . '"</info>', true);
//                    $segments = array_merge($segments, $this->readSegments($fileName, $output));
//
//                    // todo
//                    // file --> filter
//                    // filter  --> segment
//                    // segment  --> file
//
//
//                    $output->write(PHP_EOL, true);
//                }
//                $output->write(PHP_EOL . '<info>' . count($files) . ' gpx files found and processed!</info>' . PHP_EOL);
//            } else {
//                $output->write('<info>No gpx files found!</info>' . PHP_EOL);
//            }
//            $this->writeFile($dir, $segments);
//        } else {
//            throw new FileNotFoundException('The directory ' . $dir . ' does not be exist!');
//        }
//    }
//
//    /**
//     * Reads all segments from a gpx file
//     * @param string $fileName
//     * @param OutputInterface $output
//     * @return array
//     */
//    protected function readSegments($fileName, $output)
//    {
//        $result = [];
//        $doc = new \SimpleXMLElement($fileName, 0, true);
//        $doc->registerXPathNamespace('gpx', 'http://www.topografix.com/GPX/1/1');
//        $segments = $doc->xpath('//gpx:trkseg');
//        $pb = new ProgressBar($output, count($segments));
//        $pb->start();
//        foreach ($segments as $segment) {
//            $result[] = $this->processSegment($segment);
//            $pb->advance();
//        }
//        $pb->finish();
//
//        return $result;
//    }
//
//    /**
//     * Processes all points of a track segment and returns a segment model
//     * @param SimpleXmlElement $xmlSegment
//     * @return SegmentInterface
//     */
//    private function processSegment($xmlSegment)
//    {
//        $type = (string)$xmlSegment['type'];
//        $segment = (array)$xmlSegment;
//        $trackPoints = (array)$segment['trkpt'];
//
//        return $this->sm->createSegment($trackPoints, $type);
//    }
//
//    /**
//     * Writes the csv result file for all analyzed segments
//     * @param string $dir
//     * @param Segment[] $segments
//     * @param string $delimiter
//     */
//    private function writeFile($dir, array $segments, $delimiter = ';')
//    {
//        $fp = fopen($dir . '/result.csv', 'w');
//        fputcsv($fp, Segment::$ATTRIBUTES, $delimiter);
//
//        foreach ($segments as $seg) {
//            fputcsv($fp, $seg->toCSVArray(), $delimiter);
//
//        }
//        fclose($fp);
//    }

    protected function connectFilters()
    {
        new Pipe($this->frFilter, $this->tpFilter);
        new Pipe($this->tpFilter, $this->smFilter);
        new Pipe($this->smFilter, $this->fwFilter);
    }
}
