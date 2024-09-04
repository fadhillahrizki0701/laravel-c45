<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;

class DecisionTreeNodeController extends Controller
{
    public $attribute;
    public $value;
    public $children = [];
    public $isLeaf = false;
    public $label;
    public $entropy;
    public $gain;
    public $gainRatio;
    public $probability;
    public $splitInfo;

    public function __construct($attribute = null, $value = null)
    {
        $this->attribute = $attribute;
        $this->value = $value;
    }

    public function addChild(DecisionTreeNodeController $child)
    {
        $this->children[] = $child;
    }

    public function setAsLeaf($label)
    {
        $this->isLeaf = true;
        $this->label = $label;
    }

    public function setNodeAttributes($entropy, $gain, $gainRatio, $probability, $splitInfo)
    {
        $this->entropy = $entropy;
        $this->gain = $gain;
        $this->gainRatio = $gainRatio;
        $this->probability = $probability;
        $this->splitInfo = $splitInfo;
    }
}