<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Manager;

use FHV\Bundle\TmdBundle\DecisionTree\Exception\DecisionTreeException;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Decision;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class DecisionTreeBuilder
 * @package FHV\Bundle\TmdBundle\DecisionTree\Manager
 */
class DecisionTreeBuilder
{
    /**
     * @var int
     */
    protected $nodeCounter = 0;

    /**
     * @var NodeDummy[]
     */
    protected $lastOnLevel = [];

    /**
     * @var NodeDummy[]
     */
    protected $tree = [];

    /**
     * @var string
     */
    protected $txtFilePath;
    /**
     * @var string
     */
    protected $txtFileName;

    /**
     * DecisionTreeBuilder constructor.
     * @param $txtFilePath
     * @param $txtFileName
     */
    public function __construct($txtFilePath, $txtFileName)
    {
        $this->txtFilePath = $txtFilePath;
        $this->txtFileName = $txtFileName;
    }

    public function build()
    {
        $finder = new Finder();
        $finder->in($this->txtFilePath)->files()->name($this->txtFileName)->sortByName();
        $resource = [];

        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $handle = fopen($file->getRealPath(), 'r');
            $this->processFile($handle);
            $resource[] = new FileResource($file->getRealPath());
        }

        return ['tree' => $this->tree, 'resource' => $resource];
    }

    /**
     * Processes a file with a decision tree description
     * @param $handle
     */
    protected function processFile($handle)
    {
        while ($line = fgets($handle)) {
            $this->processLine($line);
        }
    }

    /**
     * Processes a line of a decision tree description
     * @param string $line
     */
    protected function processLine($line)
    {
        $tmp = explode('|   ', $line);
        $level = count($tmp) - 1; // node level
        $content = $tmp[count($tmp) - 1]; // node content

        $contentPartials = explode(' ', $content);
        $feature = $contentPartials[0];
        $comparator = $this->convertComparator($contentPartials[1]);
        $value = floatval(substr($contentPartials[2], 0, strlen($contentPartials[2]) - 1));
        $result = null;

        if (count($contentPartials) === 9) { // has results
            $result = [];
            $part = explode('=', substr($contentPartials[4], 1, strlen($contentPartials[4]) - 2));
            $result[$part[0]] = $part[1];

            $part = explode('=', substr($contentPartials[5], 0, strlen($contentPartials[5]) - 1));
            $result[$part[0]] = $part[1];

            $part = explode('=', substr($contentPartials[6], 0, strlen($contentPartials[6]) - 1));
            $result[$part[0]] = $part[1];

            $part = explode('=', substr($contentPartials[7], 0, strlen($contentPartials[7]) - 1));
            $result[$part[0]] = $part[1];

            $part = explode('=', substr($contentPartials[8], 0, strlen($contentPartials[8]) - 2));
            $result[$part[0]] = $part[1];
        }

        $this->createNode($level, $feature, $comparator, $value, $result);
    }

    /**
     * Creates a tree node
     * @param int $level
     * @param string $feature
     * @param string $comparator
     * @param float $value
     * @param array $result
     * @throws DecisionTreeException
     */
    protected function createNode($level, $feature, $comparator, $value, $result)
    {
        if (!$this->nodeWithInvertedConditionExists($level, $feature, $comparator, $value)) {
            $node = new NodeDummy();
            $name = '$node' . $this->nodeCounter;
            $node->setName($name);
            $this->nodeCounter++;
            $this->lastOnLevel[$level] = $node;
            $this->tree[$name] = $node;

            if (!($feature && $comparator && $value >= 0) && !$result) {
                throw new DecisionTreeException('Discovered node with no condition and result!');
            }

            if ($feature && $comparator && $value >= 0) {
                $node->setFeature($feature);
                $node->setComparator($comparator);
                $node->setValue($value);
            }
            if ($result) {
                $this->createResult($node, $result);
            }

            // set relations
            if ($level > 0) {
                $parent = $this->lastOnLevel[$level - 1];
                $node->setParent($parent->getName());
                if ($parent->getLeft()) {
                    $parent->setRight($name);
                } else {
                    $parent->setLeft($name);
                }
            }
        } elseif ($result) {
            $this->createResult($this->lastOnLevel[$level], $result);
        }
    }

    /**
     * Checks the last node on this level is the same but with an inverted comparator
     * @param int $level
     * @param string $feature
     * @param string $comparator
     * @param float $value
     * @return bool
     */
    protected function nodeWithInvertedConditionExists($level, $feature, $comparator, $value)
    {
        if (array_key_exists($level, $this->lastOnLevel)) {
            $existingNode = $this->lastOnLevel[$level];
            if ($feature === $existingNode->getFeature() &&
                $comparator === $this->getOppositeComparator($existingNode->getComparator()) &&
                $value === $existingNode->getValue()
            ) {
                return true;
            }

            return false;
        }
        return false;
    }

    /**
     * Creates a result node
     * @param NodeDummy $parent
     * @param array $result
     */
    protected function createResult(NodeDummy $parent, $result)
    {
        $node = new NodeDummy();
        $name = '$node' . $this->nodeCounter;
        $node->setName($name);
        $this->nodeCounter++;
        $this->tree[$name] = $node;
        $node->setParent($node->getName());
        $node->setResult($result);

        if ($parent->getLeft()) {
            $parent->setRight($name);
        } else {
            $parent->setLeft($name);
        }
    }

    /**
     * Returns the opposite comparator
     * @param $comparator
     * @return string
     */
    protected function getOppositeComparator($comparator)
    {
        switch ($comparator) {
            case '<=':
                return Decision::GT_OPERATOR;
            case '>':
                return Decision::LTEQ_OPERATOR;
            case '<':
                return Decision::GTEQ_OPERATOR;
            case '>=':
                return Decision::LT_OPERATOR;
            default:
                throw new \InvalidArgumentException('Unknown comparator ' . $comparator . ' found!');
        }
    }

    /**
     * Converts a comparator
     * @param string $comp
     * @return string
     */
    protected function convertComparator($comp)
    {
        switch ($comp) {
            case '≤':
            case '<=':
                return Decision::LTEQ_OPERATOR;
            case '≥':
            case '>=':
                return Decision::GTEQ_OPERATOR;
            case '&gt;':
            case '>':
                return Decision::GT_OPERATOR;
            case '&lt;':
            case '<':
                return Decision::LT_OPERATOR;
            default:
                throw new \InvalidArgumentException('Comparator (' . $comp . ') not supportet!');
        }
    }
}
