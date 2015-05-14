<?php

namespace FHV\Bundle\TmdBundle\DecisionTree;

// TODO implement parser to generate this class

use FHV\Bundle\TmdBundle\DecisionTree\Model\Result;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Decision;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Node;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Tree;
use FHV\Bundle\TmdBundle\Model\Feature;

/**
 * Class BasicDecissionTree
 * @package FHV\Bundle\TmdBundle\DecisionTree
 */
class BasicDecissionTree
{
    protected $tree;

    function __construct()
    {
        $root = new Node();
        $root->setDecision(new Decision(Feature::MEAN_VELOCITY, Decision::GT_OPERATOR, 20.83));

        // Level 1
        $node1 = new Node();
        $node1->setParent($root);
        $root->setLeft($node1);
        $node1->setResult(new Result(0, 0, 0, 0, 5));

        // Level 2
        $node3 = new Node();
        $node3->setParent($root);
        $root->setRight($node3);
        $node3->setDecision(new Decision(Feature::MEAN_VELOCITY, Decision::GT_OPERATOR, 2.041));

        // Level 3
        $node4 = new Node();
        $node4->setParent($node3);
        $node3->setLeft($node4);
        $node4->setDecision(new Decision(Feature::MEAN_VELOCITY, Decision::GT_OPERATOR, 7.837));

        // Level 4
        $node5 = new Node();
        $node5->setParent($node4);
        $node4->setLeft($node5);
        $node5->setDecision(new Decision(Feature::MEAN_VELOCITY, Decision::GT_OPERATOR, 8.772));

        // Level 5
        $node6 = new Node();
        $node6->setParent($node5);
        $node5->setLeft($node6);
        $node6->setResult(new Result(0, 0, 46, 1, 2));

        // Level 4
        $node7 = new Node();
        $node7->setParent($node5);
        $node5->setRight($node7);
        $node7->setDecision(new Decision(Feature::MEAN_ACCELERATION, Decision::GT_OPERATOR, 2.728));

        // Level 5
        $node8 = new Node();
        $node8->setParent($node7);
        $node7->setLeft($node8);
        $node8->setResult(new Result(0, 0, 0, 2, 0));

        $node9 = new Node();
        $node9->setParent($node7);
        $node7->setRight($node9);
        $node9->setResult(new Result(0, 0, 3, 0, 0));

        // Level 4
        $node10 = new Node();
        $node10->setParent($node4);
        $node4->setRight($node10);
        $node10->setDecision(new Decision(Feature::MEAN_ACCELERATION, Decision::GT_OPERATOR, 2.051));

        // Level 5
        $node11 = new Node();
        $node11->setParent($node10);
        $node10->setLeft($node11);
        $node11->setResult(new Result(0, 0, 0, 2, 0));

        $node12 = new Node();
        $node12->setParent($node10);
        $node10->setRight($node12);
        $node12->setDecision(new Decision(Feature::MEAN_VELOCITY, Decision::GT_OPERATOR, 6.224));

        // Level 6
        $node13 = new Node();
        $node13->setParent($node12);
        $node12->setLeft($node13);
        $node13->setDecision(new Decision(Feature::STOP_RATE, Decision::GT_OPERATOR, 0));

        // Level 7
        $node14 = new Node();
        $node14->setParent($node13);
        $node13->setLeft($node14);
        $node14->setDecision(new Decision(Feature::MAX_VELOCITY, Decision::GT_OPERATOR, 11.986));

        // Level 8
        $node15 = new Node();
        $node15->setParent($node14);
        $node14->setLeft($node15);
        $node14->setDecision(new Decision(Feature::MAX_VELOCITY, Decision::GT_OPERATOR, 14.998));

        // Level 9
        $node16 = new Node();
        $node16->setParent($node15);
        $node15->setLeft($node16);
        $node16->setResult(new Result(0, 0, 0, 5, 0));

        $node17 = new Node();
        $node17->setParent($node15);
        $node15->setRight($node17);
        $node17->setResult(new Result(1, 3, 0, 0, 0));

        // Level 8
        $node18 = new Node();
        $node18->setParent($node14);
        $node14->setRight($node18);
        $node18->setResult(new Result(2, 0, 0, 0, 0));

        // Level 7
        $node19 = new Node();
        $node19->setParent($node13);
        $node13->setRight($node19);
        $node19->setResult(new Result(6, 0, 0, 0, 0));

        // Level 6
        $node20 = new Node();
        $node20->setParent($node12);
        $node12->setRight($node20);
        $node20->setDecision(new Decision(Feature::MAX_ACCELERATION, Decision::GT_OPERATOR, 15.284));

        // Level 7
        $node21 = new Node();
        $node21->setParent($node20);
        $node20->setLeft($node21);
        $node21->setResult(new Result(1, 0, 2, 0, 0));

        $node22 = new Node();
        $node22->setParent($node20);
        $node20->setRight($node22);
        $node22->setDecision(new Decision(Feature::MEAN_VELOCITY, Decision::GT_OPERATOR, 4.395));

        // Level 8
        $node23 = new Node();
        $node23->setParent($node22);
        $node22->setLeft($node23);
        $node23->setResult(new Result(9, 0, 0, 0, 0));

        $node24 = new Node();
        $node24->setParent($node22);
        $node22->setRight($node24);
        $node24->setDecision(new Decision(Feature::MEAN_VELOCITY, Decision::GT_OPERATOR, 4.318));

        // Level 9
        $node25 = new Node();
        $node25->setParent($node24);
        $node24->setLeft($node25);
        $node25->setResult(new Result(0, 0, 2, 0, 0));

        $node26 = new Node();
        $node26->setParent($node24);
        $node24->setRight($node26);
        $node26->setDecision(new Decision(Feature::MAX_ACCELERATION, Decision::GT_OPERATOR, 3.872));

        // Level 10
        $node27 = new Node();
        $node27->setParent($node26);
        $node26->setLeft($node27);
        $node27->setResult(new Result(10, 0, 0, 0, 0));

        $node28 = new Node();
        $node28->setParent($node26);
        $node26->setRight($node28);
        $node28->setDecision(new Decision(Feature::MEAN_ACCELERATION, Decision::GT_OPERATOR, 0.914));

        // Level 11
        $node29 = new Node();
        $node29->setParent($node28);
        $node28->setLeft($node29);
        $node29->setResult(new Result(0, 0, 2, 0, 0));

        $node30 = new Node();
        $node30->setParent($node28);
        $node28->setRight($node30);
        $node30->setResult(new Result(3, 0, 0, 0, 0));

        // Level 3
        $node31 = new Node();
        $node31->setParent($node3);
        $node3->setRight($node31);
        $node31->setDecision(new Decision(Feature::MAX_VELOCITY, Decision::GT_OPERATOR, 47.534));

        // Level 4
        $node32 = new Node();
        $node32->setParent($node31);
        $node31->setLeft($node32);
        $node32->setResult(new Result(2, 0, 0, 0, 0));

        $node33 = new Node();
        $node33->setParent($node31);
        $node31->setRight($node33);
        $node33->setDecision(new Decision(Feature::STOP_RATE, Decision::GT_OPERATOR, 0.136));

        // Level 5
        $node34 = new Node();
        $node34->setParent($node33);
        $node33->setLeft($node34);
        $node34->setDecision(new Decision(Feature::STOP_RATE, Decision::GT_OPERATOR, 0.191));

        // Level 6
        $node35 = new Node();
        $node35->setParent($node34);
        $node34->setLeft($node35);
        $node35->setResult(new Result(0, 2, 0, 0, 0));

        $node36 = new Node();
        $node36->setParent($node34);
        $node34->setRight($node36);
        $node36->setResult(new Result(2, 0, 0, 0, 0));

        // Level 5
        $node37 = new Node();
        $node37->setParent($node33);
        $node33->setRight($node37);
        $node37->setDecision(new Decision(Feature::MEAN_VELOCITY, Decision::GT_OPERATOR, 1.927));

        // Level 6
        $node38 = new Node();
        $node38->setParent($node37);
        $node37->setLeft($node38);
        $node38->setResult(new Result(1, 1, 0, 0, 0));

        $node39 = new Node();
        $node39->setParent($node37);
        $node37->setRight($node39);
        $node39->setResult(new Result(1, 49, 1, 0, 0));

        $this->tree = new Tree($root);
    }

    /**
     * Process values by tree
     *
     * @param array $values
     */
    public function process(array $values)
    {
        $this->tree->process($values);
    }

    /**
     * @return Tree
     */
    public function getTree()
    {
        return $this->tree;
    }
}
