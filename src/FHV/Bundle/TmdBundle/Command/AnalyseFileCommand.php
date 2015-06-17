<?php

namespace FHV\Bundle\TmdBundle\Command;

use FHV\Bundle\TmdBundle\Manager\TrackManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Analyses all files in a given directory and analyses
 * them the same way a user would by using the graphical
 * ui.
 * Class GenerateTrainingFileCommand
 */
class AnalyseFileCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
        ->setName('tmd:analyse:files')
        ->setDescription('Analyses a directory of files.')
        ->addArgument(
            'dir',
            InputArgument::REQUIRED,
            'Define the directory with the gpx files in it.'
        )
        ->addArgument(
            'analyseMethod',
            InputArgument::OPTIONAL,
            'Define the method to use to analyse given files.',
            'basic'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('dir');
        $analyseMethod = $input->getArgument('analyseMethod');
        $fileNames = $this->getFiles($dir);
//        $dir = $this->appendSlashToDir($dir);
        $this->processFiles($dir, $fileNames, $analyseMethod, $output);
        $output->write('Finished.', true);
    }

    /**
     * Gets all gpx files from a directory
     *
     * @param string $dir
     *
     * @return array of file names
     */
    protected function getFiles($dir)
    {
        $this->validateDirectory($dir);

        return glob($dir.'/*.gpx');
    }

    /**
     * Validates the given directory path
     *
     * @param string $dir
     */
    protected function validateDirectory($dir)
    {
        if (!is_dir($dir)) {
            throw new FileNotFoundException('The directory '.$dir.' does not exist!');
        }
    }

    /**
     * Processes all given files and triggers the analyse process in the track manager
     *
     * @param string $dir
     * @param array $fileNames
     * @param string $analyseMethod
     * @param OutputInterface $output
     */
    protected function processFiles($dir, array $fileNames, $analyseMethod, OutputInterface $output)
    {
        if (count($fileNames) > 0) {
            /** @var TrackManagerInterface $trackManager */
            $trackManager = $this->getContainer()->get('fhv_tmd.trackManager');
            foreach ($fileNames as $fileName) {
                $trackManager->create(new File($fileName), $analyseMethod);
                $output->write('Analysed '.$fileName, true);
            }
        } else {
            $output->write('No gpx files found.', true);
        }
    }

    /**
     * Appends a slash to the given directory string if none is present
     *
     * @param $dir
     *
     * @return string
     */
    protected function appendSlashToDir($dir)
    {
        if (substr($dir, -1) !== '/') {
            return $dir.'/';
        }

        return $dir;
    }
}
