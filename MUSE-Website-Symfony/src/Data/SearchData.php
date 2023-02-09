<?php

namespace App\Data;

class SearchData
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Category[]
     */
    public $category = [];

    /**
     * @var Supplier[]
     */
    public $supplier = [];

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var boolean
     */
    public $discount = false;
}
