<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DecisionTreeNodeController extends Controller
{
    public $attribute;
    public $value;
    public $children = [];
    public $isLeaf = false;
    public $label;

    public function __construct($attribute = null, $value = null)
    {
        $this->attribute = $attribute;
        $this->value = $value;
    }
}
