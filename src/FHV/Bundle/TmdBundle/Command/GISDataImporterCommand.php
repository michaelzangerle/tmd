<?php

namespace FHV\Bundle\TmdBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Imports gis data from a xml file and puts it into the giscoordinate table with the specified type
 * Class GISDataImporterCommand
 * @package FHV\Bundle\TmdBundle\Command
 */
class GISDataImporterCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('tmd:import:gisdata')
            ->setDescription('Imports gis data from an xml file and persists it in the according db table.')
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'The type of the gis data (railway, highway, bus stops). This will be used to determine the entity and the db table.'
            )
            ->addArgument(
                'filePath',
                InputArgument::OPTIONAL,
                'Path to file to read from.',
                'highway.osm'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $filePath = $input->getArgument('filePath');
        if (is_file($filePath)) {
            $this->getContainer()->get('fhv_tmd.gisDataImportManager')->process($filePath, $type, $output);
            $output->write('Finished.', true);
        } else {
            $output->write('Invalid filepath given!');
        }
    }
}
