<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use FHV\Bundle\TmdBundle\Entity\Result;
use FHV\Bundle\TmdBundle\Exception\ResultNotFoundException;
use InvalidArgumentException;

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

    /**
     * Array of configured transportation modes
     *
     * @var array
     */
    protected $transportationModes;

    function __construct($em, $transportationModes)
    {
        $this->em = $em;
        $this->transportationModes = $transportationModes;
    }

    /**
     * Updates an existing result entity
     *
     * @param       $id
     * @param array $data
     *
     * @return mixed
     *
     * @throws ResultNotFoundException
     */
    public function update($id, array $data)
    {
        /** @var Result $result */
        $result = $this->em->find($this->entityName, $id);
        if ($result && array_key_exists('transport_type', $data)) {

            $this->validationTransportationType($data['transport_type']);
            $type = $data['transport_type'];
            $result->setCorrectedTransportType($type);
            $this->em->flush();

            return $result;
        }

        throw new ResultNotFoundException($id);
    }

    /**
     * Validates the given transport type against the configured
     *
     * @param string $transportType
     *
     * @throws InvalidArgumentException
     */
    protected function validationTransportationType($transportType)
    {
        if (array_search($transportType, $this->transportationModes) === false) {
            throw new InvalidArgumentException('The provided transportation type '.$transportType.' is invalid!');
        }
    }
}
