<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Component\ComponentInterface;

/**
 * Represents a file writer and offers the possibility to set certain options
 * Interface FileWriterInterface
 * @package FHV\Bundle\TmdBundle\Filter
 */
interface FileWriterInterface extends ComponentInterface
{
    /**
     * @return string
     */
    public function getFilePath();

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath);

    /**
     * @return string
     */
    public function getDelimiter();

    /**
     * @param string $delimiter
     */
    public function setDelimiter($delimiter);
}
