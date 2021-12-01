<?php


namespace Tests\helper;


use PHPUnit\Framework\TestCase;
use Tests\mock\TapMock;

class TapHelperTest  extends testcase
{

    /** @test */
    public function it_can_return_the_tap_value_while_triggering_a_callback()
    {
        $tap = new TapMock;
        $this->assertNull( $tap->getDummy());
        $tap = tap($tap,fn($tap) => $tap->setDummy('tapping is great'));
        $this->assertEquals('tapping is great', $tap->getDummy());
        $this->assertInstanceOf(TapMock::class, $tap);
    }


    /** @test */
    public function it_can_call_dynamically_if_tap_callback_is_not_provided()
    {
        $tap = new TapMock;
        $this->assertNull( $tap->getDummy());
        $tap = tap($tap)->setDummy('tapping is great');
        $this->assertEquals('tapping is great', $tap->getDummy());
    }


}