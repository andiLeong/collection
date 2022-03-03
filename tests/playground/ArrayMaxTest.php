<?php


namespace Tests\playground;


use Andileong\Collection\Collection;
use PHPUnit\Framework\TestCase;

class ArrayMaxTest extends testcase
{

    /** @test */
    public function it_can_get_max_value_from_array_without_using_array_max_with_foreach()
    {
        $max = 0;
        $array = [33,45,1,2,999,456];

        foreach($array as $index => $value){
            if($index == 0){
                continue;
            }

            $previous = $array[$index - 1];
            if( $previous >= $value){
                $max = $previous;
            }else{
                $max = $value;
            }
        }

        $this->assertEquals(999,$max);
    }

    /** @test */
    public function it_can_get_max_value_from_array_without_using_array_max_with_reduce()
    {
        $array = [33,45,1,2,999,456];

        $max = array_reduce($array,function($carry,$value) use($array){
            $index = array_search($value,$array);
            if($index == 0){
                return $carry;
            }

            $previous = $array[$index - 1];
            if( $previous >= $value){
                $carry = $previous;
            }else{
                $carry = $value;
            }

            return $carry;

        },0);

        $this->assertEquals(999,$max);
    }

    /** @test */
    public function it_can_get_max_value_from_array_without_using_array_max_with_array_walk()
    {
        $max = 0;
        $array = [33,45,1,2,999,456];

        array_walk($array,function($value,$index) use($array,&$max){
            if($index != 0) {
                $previous = $array[$index - 1];
                if ($previous >= $value) {
                    $max = $previous;
                } else {
                    $max = $value;
                }
            }
        });

        $this->assertEquals(999,$max);
    }

    /** @test */
    public function it_can_get_max_value_from_array_without_using_array_max_with_array_map()
    {
        $array = [33,45,1,2,999,456];
        $max = 0 ;
        array_map(function($value) use($array, &$max){

            $index = array_search($value,$array);
            if($index == 0) {
                return $max = 0;
            }

            $previous = $array[$index - 1];
            if ($previous >= $value) {
                $max = $previous;
            }else{
                $max = $value;
            }
        },$array);

        $this->assertEquals(999,$max);
    }

    /** @test */
    public function it_can_get_max_value_from_array_without_using_array_max_with_array_filter()
    {
        $array = [33,45,1,2,999,456];
        $max = array_filter($array,function($value,$index) use($array) {
            if($index == 0) {
                return true;
            }

            return $array[$index - 1] <= $value;
        } ,ARRAY_FILTER_USE_BOTH);

        $this->assertEquals(999,end($max));
    }
}