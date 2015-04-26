<?php

namespace FHV\Bundle\TmdBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class TrackManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class TrackManager implements TrackManagerInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Creates a track from a gxp file and a for a selected process method
     *
     * @param File   $file
     * @param string $method
     *
     * @return mixed
     */
    public function create(File $file, $method)
    {
        // TODO: Implement create() method.
        echo "hello world1";
        return null;
    }
}
