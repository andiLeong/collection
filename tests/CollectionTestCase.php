<?php
namespace Tests;


use Andileong\Collection\Collection;
use PHPUnit\Framework\TestCase;

abstract class CollectionTestCase extends testcase
{
    public array $array;
    public array $associateArray;
    public $collection;
    public $associateCollection;

    public function setUp() :void
    {
        parent::setUp();

        $this->array = ['one','two','three','four'];

        $this->associateArray = [
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 100,
            'discount' => false,
        ];


        $this->collection = Collection::make($this->array);
        $this->associateCollection = Collection::make($this->associateArray);

    }

}