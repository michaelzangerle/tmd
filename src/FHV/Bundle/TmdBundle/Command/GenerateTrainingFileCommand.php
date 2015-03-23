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
                'fileName',
                InputArgument::OPTIONAL,
                'Define the filename for the results.',
                'result.csv'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('dir');
        $fileName = $input->getArgument('fileName');
        $this->getContainer()->get('fhv_tmd.trainingDataManager')->process($output, $dir, $fileName);
        $output->write('Finished.', true);
    }
}
