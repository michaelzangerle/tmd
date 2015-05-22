<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface GISDataImportManagerInterface
 * @package FHV\Bundle\TmdBundle\Manager
 */
interface GISDataImportManagerInterface {

    /**
     * Processes an xml file
     * @param string $fileName
     * @param string $type
     * @param OutputInterface $output
     * @return mixed
     */
    public function process($fileName, $type,OutputInterface $output);
}
