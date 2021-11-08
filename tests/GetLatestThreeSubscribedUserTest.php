<?php
namespace tests;

use Andileong\Collection\Collection;
use PHPUnit\Framework\TestCase;

class GetLatestThreeSubscribedUserTest extends testcase
{

    /** @test */
    public function it_can_get_latest_three_subscribed_users()
    {

        $array = [
            [
                'id' => 1,
                'name' => 'andi fake',
                'is_subscribed' => true,
                'created_at' => '2021-05-07T09:51:51.000000Z',
            ],
            [
                'id' => 2,
                'name' => 'Betty Waters',
                'is_subscribed' => false,
                'created_at' => '2021-04-07T09:51:51.000000Z',
            ],
            [
                'id' => 3,
                'name' => 'Landen Spinka',
                'is_subscribed' => true,
                'created_at' => '2021-01-07T09:51:51.000000Z',
            ],
            [
                'id' => 4,
                'name' => 'Dell Stoltenberg',
                'is_subscribed' => false,
                'created_at' => '2021-09-07T09:51:51.000000Z',
            ],
            [
                'id' => 5,
                'name' => 'Guy Moen PhD',
                'is_subscribed' => true,
                'created_at' => '2021-07-07T09:51:51.000000Z',
            ],
            [
                'id' => 6,
                'name' => 'Ida Swaniawski',
                'is_subscribed' => false,
                'created_at' => '2021-05-07T09:51:51.000000Z',
            ],
            [
                'id' => 7,
                'name' => 'Norberto Baumbach',
                'is_subscribed' => false,
                'created_at' => '2021-06-07T09:51:51.000000Z',
            ],
            [
                'id' => 8,
                'name' => 'McDermott',
                'is_subscribed' => true,
                'created_at' => '2021-08-07T09:51:51.000000Z',
            ],
            [
                'id' => 9,
                'name' => 'Lavon Nitzsche DDS',
                'is_subscribed' => true,
                'created_at' => '2021-06-07T09:51:51.000000Z',
            ],
            [
                'id' => 10,
                'name' => 'Natalie Huel',
                'is_subscribed' => true,
                'created_at' => '2021-01-07T09:51:51.000000Z',
            ],
        ];

        $collection = Collection::make($array);
        $collection = $collection
            ->filter( fn($user) => $user['is_subscribed'] )
            ->map( function ($user) {
                $user['name'] = strtoupper($user['name']);
                return $user;
            })
            ->sortBy('created_at', SORT_DESC)
            ->limit(3);

        $this->assertEquals( 8 , $collection[0]['id']);
        $this->assertEquals( strtoupper('McDermott') , $collection[0]['name']);
        $this->assertEquals( 9 , $collection[2]['id']);
    }


}