<?php

namespace Tests;


use Andileong\Collection\Container;
use PHPUnit\Framework\TestCase;
use Tests\mock\ExampleStub;
use Tests\mock\TapMock;

class ContainerTest extends testcase
{
    private $container;

    public function setUp() :void
    {
        parent::setUp();
        $this->container = new Container();
    }

    /** @test */
    public function it_can_fetch_a_value_from_a_container_binding()
    {
        $this->container->bind('foo', 'bar');
        $value = $this->container->get('foo');
        $this->assertEquals('bar', $value);
    }

    /** @test */
    public function it_can_bind_a_closure_to_the_container()
    {
        $this->container->bind('example', fn() => new ExampleStub());
        $this->assertInstanceOf(ExampleStub::class,$this->container->get('example'));
    }

    /** @test */
    public function it_can_bind_a_singleton_closure_to_the_container()
    {
        $this->container->singleton('example', fn() => new ExampleStub());
        $example = $this->container->get('example');
        $example1 = $this->container->get('example');
        $this->assertTrue($example === $example1);
    }

    /** @test */
    public function it_can_build_up_object_when_key_is_not_existed_inside_the_container()
    {
        $example = $this->container->get(ExampleStub::class);
        $this->assertInstanceOf(ExampleStub::class,$example);
    }

    /** @test */
    public function it_can_allow_array_access_for_container()
    {
        $this->container->bind('foo', 'bar');
        $this->container->bind('example', 'baz');
        $this->assertEquals('bar',$this->container['foo']['concrete']);
        $this->assertEquals('baz',$this->container['example']['concrete']);
    }

}