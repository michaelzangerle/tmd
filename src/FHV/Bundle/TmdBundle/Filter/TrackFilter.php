<?php
/**
 * Created by IntelliJ IDEA.
 * User: mike
 * Date: 26.04.15
 * Time: 11:47
 */

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;

class TrackFilter extends AbstractFilter
{

    // getter and setter for entities

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws FilterException
     */
    public function run($data)
    {
        // collects segment entities
    }

    public function parentHasFinished(){

        // write data to database

    }
}

