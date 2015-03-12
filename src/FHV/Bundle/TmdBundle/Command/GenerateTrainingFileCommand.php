<?php

namespace FHV\Bundle\TmdBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generates the training data file needed to build a decision tree
 * Needs at least one gpx file to generate the training data file from it
 * Class GenerateDecissionTreeFileCommand
 */
class GenerateTrainingFileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tmd:generate:trainingdata')
            ->setDescription('Generates the training data file needed to build a decision tree.')
            ->addArgument(
                'dir',
                InputArgument::REQUIRED,
                'Define the directory with the gpx files in it.'
            )
            ->addArgument(
                'format',
                InputArgument::OPTIONAL,
                'Define the output format of the trainings file [csv,separated]'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('dir');
        $format = $input->getArgument('format');

        if ($format === null || ($format !== 'csv' && $format !== 'separated')) {
            $format = 'csv';
        }

        $this->getContainer()->get('fhv_tmd.trainingDataManager')->process($dir, $format, $output);
        $output->write('Finished.', true);
    }
}