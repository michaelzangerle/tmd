<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use FHV\Bundle\TmdBundle\Entity\Result;
use FHV\Bundle\TmdBundle\Exception\ResultNotFoundException;

/**
 * Manages all operations for results
 * Class ResultManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class ResultManager implements ResultManagerInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityName = 'FHVTmdBundle:Result';

    function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Updates an existing result entity
     *
     * @param       $id
     * @param array $data
     *
     * @return mixed
     * @throws ResultNotFoundException
     */
    public function update($id, array $data)
    {
        /** @var Result $result */
        $result = $this->em->find($this->entityName, $id);
        if ($result) {
            if (array_key_exists('transport_type', $data)) {
                // TODO check if given type is valid
                $type = $data['transport_type'];
                $result->setCorrectedTransportType($type);
                $this->em->flush();

                return $result;
            }
        }

        throw new ResultNotFoundException($id);
    }
}
