<?php
namespace Tests;


class CollectionFormatTest extends CollectionTestCase
{
    public function setUp() :void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_covert_collection_to_array()
    {
        $collection = $this->collection->toArray();
        $this->assertIsArray($collection);
    }

    /** @test */
    public function it_can_covert_collection_to_json()
    {
        $collection = $this->collection->toJson();
        $this->assertJson($collection);
    }

    /** @test */
    public function it_can_be_render_as_json()
    {
        $this->assertJson(json_encode($this->collection));
    }
}