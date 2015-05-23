<?php

use FHV\Bundle\TmdBundle\DecisionTree\Model\Result;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Decision;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Node;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Tree;
use FHV\Bundle\TmdBundle\Model\Feature;
use FHV\Bundle\TmdBundle\DecisionTree\DecisionTreeInterface;

/**
 * DO NOT EDIT - This class is autogenerated by the the decission tree manager
 * Class BasicDecisionTree
 * @package FHV\Bundle\TmdBundle\DecisionTree
 */
class BasicDecisionTree implements DecisionTreeInterface
{
    protected $tree;

    function __construct()
    {
                    $node0 = new Node();
                    $node0->setDecision(new Decision('meanvelocity', '>', 2.041));
                                    $node1 = new Node();
                    $node1->setDecision(new Decision('meanvelocity', '>', 20.83));
                                    $node2 = new Node();
                            $node2->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 5,
                        ]));
                            $node3 = new Node();
                    $node3->setDecision(new Decision('meanvelocity', '>', 7.922));
                                    $node4 = new Node();
                    $node4->setDecision(new Decision('meanvelocity', '>', 8.6));
                                    $node5 = new Node();
                    $node5->setDecision(new Decision('maxacceleration', '>', 9.254));
                                    $node6 = new Node();
                    $node6->setDecision(new Decision('maxvelocity', '>', 25.33));
                                    $node7 = new Node();
                    $node7->setDecision(new Decision('maxvelocity', '>', 27.466));
                                    $node8 = new Node();
                            $node8->setResult(new Result([
                            'bus' => 0,
                            'drive' => 19,
                            'walk' => 0,
                            'bike' => 0,
                            'train' => 1,
                        ]));
                            $node9 = new Node();
                            $node9->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 2,
                        ]));
                            $node10 = new Node();
                    $node10->setDecision(new Decision('meanvelocity', '>', 10.521));
                                    $node11 = new Node();
                            $node11->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 2,
                            'train' => 0,
                        ]));
                            $node12 = new Node();
                            $node12->setResult(new Result([
                            'bike' => 0,
                            'drive' => 2,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node13 = new Node();
                            $node13->setResult(new Result([
                            'bike' => 0,
                            'drive' => 34,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node14 = new Node();
                    $node14->setDecision(new Decision('meanvelocity', '>', 8.43));
                                    $node15 = new Node();
                            $node15->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 2,
                            'train' => 0,
                        ]));
                            $node16 = new Node();
                            $node16->setResult(new Result([
                            'bus' => 1,
                            'drive' => 6,
                            'walk' => 0,
                            'bike' => 0,
                            'train' => 0,
                        ]));
                            $node17 = new Node();
                    $node17->setDecision(new Decision('meanacceleration', '>', 2.501));
                                    $node18 = new Node();
                            $node18->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 4,
                            'train' => 0,
                        ]));
                            $node19 = new Node();
                    $node19->setDecision(new Decision('meanvelocity', '>', 6.224));
                                    $node20 = new Node();
                    $node20->setDecision(new Decision('maxvelocity', '>', 15.067));
                                    $node21 = new Node();
                    $node21->setDecision(new Decision('meanvelocity', '>', 7.008));
                                    $node22 = new Node();
                            $node22->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 4,
                            'train' => 0,
                        ]));
                            $node23 = new Node();
                            $node23->setResult(new Result([
                            'bike' => 1,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 1,
                            'train' => 0,
                        ]));
                            $node24 = new Node();
                    $node24->setDecision(new Decision('stoprate', '>', 0.002));
                                    $node25 = new Node();
                            $node25->setResult(new Result([
                            'bike' => 0,
                            'drive' => 2,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node26 = new Node();
                    $node26->setDecision(new Decision('meanacceleration', '>', 1.609));
                                    $node27 = new Node();
                    $node27->setDecision(new Decision('meanvelocity', '>', 7.169));
                                    $node28 = new Node();
                    $node28->setDecision(new Decision('meanvelocity', '>', 7.369));
                                    $node29 = new Node();
                            $node29->setResult(new Result([
                            'bike' => 2,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node30 = new Node();
                            $node30->setResult(new Result([
                            'bike' => 0,
                            'drive' => 2,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node31 = new Node();
                            $node31->setResult(new Result([
                            'bike' => 4,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node32 = new Node();
                            $node32->setResult(new Result([
                            'bike' => 1,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 1,
                            'train' => 0,
                        ]));
                            $node33 = new Node();
                    $node33->setDecision(new Decision('maxacceleration', '>', 15.284));
                                    $node34 = new Node();
                            $node34->setResult(new Result([
                            'bike' => 1,
                            'drive' => 2,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node35 = new Node();
                    $node35->setDecision(new Decision('meanvelocity', '>', 4.395));
                                    $node36 = new Node();
                            $node36->setResult(new Result([
                            'bike' => 9,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node37 = new Node();
                    $node37->setDecision(new Decision('meanvelocity', '>', 4.318));
                                    $node38 = new Node();
                            $node38->setResult(new Result([
                            'bike' => 0,
                            'drive' => 2,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node39 = new Node();
                    $node39->setDecision(new Decision('maxacceleration', '>', 3.872));
                                    $node40 = new Node();
                            $node40->setResult(new Result([
                            'bike' => 10,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node41 = new Node();
                    $node41->setDecision(new Decision('meanacceleration', '>', 0.914));
                                    $node42 = new Node();
                            $node42->setResult(new Result([
                            'bike' => 0,
                            'drive' => 2,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node43 = new Node();
                            $node43->setResult(new Result([
                            'bike' => 3,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node44 = new Node();
                    $node44->setDecision(new Decision('maxvelocity', '>', 47.534));
                                    $node45 = new Node();
                            $node45->setResult(new Result([
                            'bike' => 2,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node46 = new Node();
                    $node46->setDecision(new Decision('stoprate', '>', 0.136));
                                    $node47 = new Node();
                    $node47->setDecision(new Decision('stoprate', '>', 0.191));
                                    $node48 = new Node();
                            $node48->setResult(new Result([
                            'bike' => 0,
                            'drive' => 0,
                            'walk' => 2,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node49 = new Node();
                            $node49->setResult(new Result([
                            'bike' => 2,
                            'drive' => 0,
                            'walk' => 0,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node50 = new Node();
                    $node50->setDecision(new Decision('meanvelocity', '>', 1.927));
                                    $node51 = new Node();
                            $node51->setResult(new Result([
                            'bike' => 1,
                            'drive' => 0,
                            'walk' => 1,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                            $node52 = new Node();
                            $node52->setResult(new Result([
                            'bike' => 1,
                            'drive' => 1,
                            'walk' => 52,
                            'bus' => 0,
                            'train' => 0,
                        ]));
                
                                    $node0->setLeft($node1);
                            $node0->setRight($node44);
                                    $node1->setParent($node0);
                            $node1->setLeft($node2);
                            $node1->setRight($node3);
                                    $node2->setParent($node2);
                                                    $node3->setParent($node1);
                            $node3->setLeft($node4);
                            $node3->setRight($node17);
                                    $node4->setParent($node3);
                            $node4->setLeft($node5);
                            $node4->setRight($node14);
                                    $node5->setParent($node4);
                            $node5->setLeft($node6);
                            $node5->setRight($node13);
                                    $node6->setParent($node5);
                            $node6->setLeft($node7);
                            $node6->setRight($node10);
                                    $node7->setParent($node6);
                            $node7->setLeft($node8);
                            $node7->setRight($node9);
                                    $node8->setParent($node8);
                                                    $node9->setParent($node9);
                                                    $node10->setParent($node6);
                            $node10->setLeft($node11);
                            $node10->setRight($node12);
                                    $node11->setParent($node11);
                                                    $node12->setParent($node12);
                                                    $node13->setParent($node13);
                                                    $node14->setParent($node4);
                            $node14->setLeft($node15);
                            $node14->setRight($node16);
                                    $node15->setParent($node15);
                                                    $node16->setParent($node16);
                                                    $node17->setParent($node3);
                            $node17->setLeft($node18);
                            $node17->setRight($node19);
                                    $node18->setParent($node18);
                                                    $node19->setParent($node17);
                            $node19->setLeft($node20);
                            $node19->setRight($node33);
                                    $node20->setParent($node19);
                            $node20->setLeft($node21);
                            $node20->setRight($node24);
                                    $node21->setParent($node20);
                            $node21->setLeft($node22);
                            $node21->setRight($node23);
                                    $node22->setParent($node22);
                                                    $node23->setParent($node23);
                                                    $node24->setParent($node20);
                            $node24->setLeft($node25);
                            $node24->setRight($node26);
                                    $node25->setParent($node25);
                                                    $node26->setParent($node24);
                            $node26->setLeft($node27);
                            $node26->setRight($node32);
                                    $node27->setParent($node26);
                            $node27->setLeft($node28);
                            $node27->setRight($node31);
                                    $node28->setParent($node27);
                            $node28->setLeft($node29);
                            $node28->setRight($node30);
                                    $node29->setParent($node29);
                                                    $node30->setParent($node30);
                                                    $node31->setParent($node31);
                                                    $node32->setParent($node32);
                                                    $node33->setParent($node19);
                            $node33->setLeft($node34);
                            $node33->setRight($node35);
                                    $node34->setParent($node34);
                                                    $node35->setParent($node33);
                            $node35->setLeft($node36);
                            $node35->setRight($node37);
                                    $node36->setParent($node36);
                                                    $node37->setParent($node35);
                            $node37->setLeft($node38);
                            $node37->setRight($node39);
                                    $node38->setParent($node38);
                                                    $node39->setParent($node37);
                            $node39->setLeft($node40);
                            $node39->setRight($node41);
                                    $node40->setParent($node40);
                                                    $node41->setParent($node39);
                            $node41->setLeft($node42);
                            $node41->setRight($node43);
                                    $node42->setParent($node42);
                                                    $node43->setParent($node43);
                                                    $node44->setParent($node0);
                            $node44->setLeft($node45);
                            $node44->setRight($node46);
                                    $node45->setParent($node45);
                                                    $node46->setParent($node44);
                            $node46->setLeft($node47);
                            $node46->setRight($node50);
                                    $node47->setParent($node46);
                            $node47->setLeft($node48);
                            $node47->setRight($node49);
                                    $node48->setParent($node48);
                                                    $node49->setParent($node49);
                                                    $node50->setParent($node46);
                            $node50->setLeft($node51);
                            $node50->setRight($node52);
                                    $node51->setParent($node51);
                                                    $node52->setParent($node52);
                                
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
