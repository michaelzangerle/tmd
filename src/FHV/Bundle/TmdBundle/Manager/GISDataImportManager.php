<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use FHV\Bundle\TmdBundle\Entity\GISCoordinate;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Imports gis data to the database
 * Class GISDataImportManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class GISDataImportManager implements GISDataImportManagerInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var int
     */
    protected $batchSize = 100;

    /**
     * GISDataImportManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function process($fileName, $type, OutputInterface $output)
    {
        $doc = new \SimpleXMLElement($fileName, 0, true);
        $nodes = $doc->xpath('//node');
        $typeName = $this->getValidType($type);
        $counter = 1;

        if (count($nodes) > 0 && $typeName) {
            foreach ($nodes as $node) {
                $nodeEntity = $this->createGISCoordinate($node, $typeName);
                $this->em->persist($nodeEntity);
                $output->write($counter . ' ');

                if ($counter % $this->batchSize === 0) {
                    $this->em->flush();
                    $output->writeln(PHP_EOL.'Flushed '. $counter);
                }
                $counter++;
            }

            $this->em->flush();
            $output->writeln(PHP_EOL.'Flushed rest!');
        } else {
            throw new \InvalidArgumentException(
                'Invalid arguments passed to the import manager (no nodes found or invalid type)!'
            );
        }
    }

    /**
     * Creates a node entity from an xml node
     * @param $node
     * @param $type
     * @return GISCoordinate
     */
    protected function createGISCoordinate($node, $type)
    {
        $lat = floatval($node['lat']);
        $lon = floatval($node['lon']);

        return new GISCoordinate($lat, $lon, $type);
    }

    /**
     * Returns an entity name for the passed type
     * @param string $type
     * @return string
     */
    protected function getValidType($type)
    {
        switch ($type) {
            case 'rail':
            case 'railway':
            case 'train':
                return GISCoordinate::RAILWAY_TYPE;
            case 'highway':
            case 'motorway':
                return GISCoordinate::HIGHWAY_TYPE;
            case 'busstop':
            case 'public_transport_stop':
            case 'bus_stop':
                return GISCoordinate::BUSSTOP_TYPE;
            default:
                return null;
        }
    }
}
