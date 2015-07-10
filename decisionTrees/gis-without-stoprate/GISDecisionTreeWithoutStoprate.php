<?php

use FHV\Bundle\TmdBundle\DecisionTree\Model\Result;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Decision;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Node;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Tree;
use FHV\Bundle\TmdBundle\Model\Feature;
use FHV\Bundle\TmdBundle\DecisionTree\DecisionTreeInterface;

/**
 * DO NOT EDIT - This class is autogenerated by the the decission tree manager
 * Class GISDecisionTreeWithoutStoprate
 * @package FHV\Bundle\TmdBundle\DecisionTree
 */
class GISDecisionTreeWithoutStoprate implements DecisionTreeInterface
{
    protected $tree;

    function __construct()
    {
                    $node0 = new Node();
                    $node0->setDecision(new Decision('meanvelocity', '>', 2.044));
                                    $node1 = new Node();
                    $node1->setDecision(new Decision('meanvelocity', '>', 7.887));
                                    $node2 = new Node();
                    $node2->setDecision(new Decision('railcloseness', '>', 0.012));
                                    $node3 = new Node();
                            $node3->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 8,
                        ]));
                            $node4 = new Node();
                    $node4->setDecision(new Decision('ptscloseness', '>', 20.5));
                                    $node5 = new Node();
                    $node5->setDecision(new Decision('railcloseness', '>', 0));
                                    $node6 = new Node();
                            $node6->setResult(new Result([
                            'bike' => 0,
                            'drive' => 1,
                            'walk' => 0,
                            'bus' => 1,
                            'train' => 0,
                        ]));
                            $node7 = new Node();
                            $node7->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 2,
                            'train' => 0,
                        ]));
                            $node8 = new Node();
                            $node8->setResult(new Result([
                            'bus' => 2,
                            'drive' => 60,
                            'walk' => 0,
                            'bike' => 0,
                            'train' => 0,
                        ]));
                            $node9 = new Node();
                    $node9->setDecision(new Decision('meanvelocity', '>', 7.594));
                                    $node10 = new Node();
                            $node10->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 4,
                            'train' => 0,
                        ]));
                            $node11 = new Node();
                    $node11->setDecision(new Decision('railcloseness', '>', 0));
                                    $node12 = new Node();
                            $node12->setResult(new Result([
                            'bike' => 16,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node13 = new Node();
                    $node13->setDecision(new Decision('maxvelocity', '>', 10.906));
                                    $node14 = new Node();
                    $node14->setDecision(new Decision('ptscloseness', '>', 2));
                                    $node15 = new Node();
                    $node15->setDecision(new Decision('maxvelocity', '>', 15.801));
                                    $node16 = new Node();
                            $node16->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 4,
                            'train' => 0,
                        ]));
                            $node17 = new Node();
                            $node17->setResult(new Result([
                            'bike' => 2,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 1,
                            'train' => 0,
                        ]));
                            $node18 = new Node();
                    $node18->setDecision(new Decision('highwaycloseness', '>', 0.001));
                                    $node19 = new Node();
                            $node19->setResult(new Result([
                            'bike' => 3,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node20 = new Node();
                    $node20->setDecision(new Decision('maxvelocity', '>', 15.561));
                                    $node21 = new Node();
                    $node21->setDecision(new Decision('maxvelocity', '>', 30.207));
                                    $node22 = new Node();
                            $node22->setResult(new Result([
                            'bike' => 0,
                            'drive' => 2,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node23 = new Node();
                            $node23->setResult(new Result([
                            'bike' => 3,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node24 = new Node();
                            $node24->setResult(new Result([
                            'bus' => 1,
                            'drive' => 7,
                            'walk' => 0,
                            'bike' => 0,
                            'train' => 0,
                        ]));
                            $node25 = new Node();
                    $node25->setDecision(new Decision('maxacceleration', '>', 2.31));
                                    $node26 = new Node();
                            $node26->setResult(new Result([
                            'bike' => 8,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node27 = new Node();
                    $node27->setDecision(new Decision('meanacceleration', '>', 0.866));
                                    $node28 = new Node();
                            $node28->setResult(new Result([
                            'bike' => 0,
                            'drive' => 2,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node29 = new Node();
                            $node29->setResult(new Result([
                            'bike' => 3,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node30 = new Node();
                    $node30->setDecision(new Decision('maxvelocity', '>', 44.538));
                                    $node31 = new Node();
                            $node31->setResult(new Result([
                            'bike' => 2,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node32 = new Node();
                            $node32->setResult(new Result([
                            'bike' => 4,
                            'drive' => 1,
                            'walk' => 53,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                
                                    $node0->setLeft($node1);
                            $node0->setRight($node30);
                                    $node1->setParent($node0);
                            $node1->setLeft($node2);
                            $node1->setRight($node9);
                                    $node2->setParent($node1);
                            $node2->setLeft($node3);
                            $node2->setRight($node4);
                                    $node3->setParent($node3);
                                                    $node4->setParent($node2);
                            $node4->setLeft($node5);
                            $node4->setRight($node8);
                                    $node5->setParent($node4);
                            $node5->setLeft($node6);
                            $node5->setRight($node7);
                                    $node6->setParent($node6);
                                                    $node7->setParent($node7);
                                                    $node8->setParent($node8);
                                                    $node9->setParent($node1);
                            $node9->setLeft($node10);
                            $node9->setRight($node11);
                                    $node10->setParent($node10);
                                                    $node11->setParent($node9);
                            $node11->setLeft($node12);
                            $node11->setRight($node13);
                                    $node12->setParent($node12);
                                                    $node13->setParent($node11);
                            $node13->setLeft($node14);
                            $node13->setRight($node25);
                                    $node14->setParent($node13);
                            $node14->setLeft($node15);
                            $node14->setRight($node18);
                                    $node15->setParent($node14);
                            $node15->setLeft($node16);
                            $node15->setRight($node17);
                                    $node16->setParent($node16);
                                                    $node17->setParent($node17);
                                                    $node18->setParent($node14);
                            $node18->setLeft($node19);
                            $node18->setRight($node20);
                                    $node19->setParent($node19);
                                                    $node20->setParent($node18);
                            $node20->setLeft($node21);
                            $node20->setRight($node24);
                                    $node21->setParent($node20);
                            $node21->setLeft($node22);
                            $node21->setRight($node23);
                                    $node22->setParent($node22);
                                                    $node23->setParent($node23);
                                                    $node24->setParent($node24);
                                                    $node25->setParent($node13);
                            $node25->setLeft($node26);
                            $node25->setRight($node27);
                                    $node26->setParent($node26);
                                                    $node27->setParent($node25);
                            $node27->setLeft($node28);
                            $node27->setRight($node29);
                                    $node28->setParent($node28);
                                                    $node29->setParent($node29);
                                                    $node30->setParent($node0);
                            $node30->setLeft($node31);
                            $node30->setRight($node32);
                                    $node31->setParent($node31);
                                                    $node32->setParent($node32);
                                
        $this->tree = new Tree($node0);
    }

    /**
    * Process values by tree
    *
    * @param array $values
    *
    * @return array
    */
    public function process(array $values)
    {
        return $this->tree->process($values);
    }
}
