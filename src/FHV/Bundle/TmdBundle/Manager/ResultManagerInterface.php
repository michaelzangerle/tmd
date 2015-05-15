<?php

namespace FHV\Bundle\TmdBundle\Manager;

use FHV\Bundle\TmdBundle\Exception\ResultNotFoundException;

/**
 * Interface ResultManagerInterface
 * @package FHV\Bundle\TmdBundle\Manager
 */
interface ResultManagerInterface {

    /**
     * Updates an existing result entity
     *
     * @param       $id
     * @param array $data
     *
     * @return mixed
     * @throws ResultNotFoundException
     */
    public function update($id, array $data);
}
