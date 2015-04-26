<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;

use FHV\Bundle\TmdBundle\Entity\Tracksegment as SegmentEntity;

use FHV\Bundle\TmdBundle\Model\TrackpointInterface;
use FHV\Bundle\TmdBundle\Model\TracksegmentInterface;
use FHV\Bundle\TmdBundle\Model\Trackpoint;
use FHV\Bundle\TmdBundle\Util\TrackpointUtil;

class SegmentationFilter extends AbstractFilter
{
    /**
     * @var TrackpointUtil
     */
    private $util;

    function __construct(TrackpointInterface $util)
    {
        $this->util = $util;
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
            $this->process($data);
            if ($this->getParentHasFinished()) {
                $this->finished();
            }
        } else {
            throw new FilterException('Invalid data. Data must implement SegmentInterface!');
        }
    }

    /**
     * Segments the gps segment into single transportation mode segments
     *
     * @param TracksegmentInterface $gpsSegment
     */
    private function process(TracksegmentInterface $gpsSegment)
    {
        $segments = $this->createSegments($gpsSegment);
        // gehn und nicht geh punkte anhand von obergrenze für beschleunigung und geschwindigkeit bestimmen
        // diese bereits in geh und nicht gehsegmente einteile
        // mergen von segmenten mit vorherigem segment wenn unter mindest distanz oder zeit (200m)
        // segmente mit bestimmter mindestlänge wird als sicheres segment betrachtet - ansonsten unsicheres segment
        // bestimmte anzahl von aufeinanderfolgenden unsicheren segementen werden in nicht geh segment gemerged
        // start und endpunkt eines

        $this->write($segments);
    }

    /**
     * Creates basic segments from gps segment
     *
     * @param TracksegmentInterface $gpsSegment
     *
     * @return SegmentEntity[]
     */
    private function createSegments(TracksegmentInterface $gpsSegment)
    {
        $segments = [];
        $i = 0;
        $next = 1;
        $length = count($gpsSegment->getTrackPoints());
        $curSegment = new SegmentEntity();

        while ($next < $length) {
            /** @var TrackPoint $tp1 */
            $tp1 = $gpsSegment->getTrackPoints()[$i];

            /** @var TrackPoint $tp2 */
            $tp2 = $gpsSegment->getTrackPoints()[$next];

            $next++;
            $i++;
        }

        return $segments;
    }
}
