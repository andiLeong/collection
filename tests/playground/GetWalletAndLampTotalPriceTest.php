<?php
namespace Tests\playground;

use Andileong\Collection\Collection;
use PHPUnit\Framework\TestCase;

class GetWalletAndLampTotalPriceTest extends testcase
{

    /** @test */
    public function it_can_get_total_price_of_lamp_and_wallet()
    {

        $json = '{
            "products": [
            {
            "title": "Small Rubber Wallet",
            "product_type": "Wallet",
            "variants": [
            { "title": "Blue", "price": 29.33 },
            { "title": "Turquoise", "price": 18.50 }
            ]
            },
            {
            "title": "Sleek Cotton Shoes",
            "product_type": "Shoes",
            "variants": [
            { "title": "Sky Blue", "price": 20.00 }
            ]
            },
            {
            "title": "Intelligent Cotton Wallet",
            "product_type": "Wallet",
            "variants": [
            { "title": "White", "price": 17.97 }
            ]
            },
            {
            "title": "Enormous Leather Lamp",
            "product_type": "Lamp",
            "variants": [
            { "title": "Azure", "price": 65.99 },
            { "title": "Salmon", "price": 1.66 }
            ]
            }

            ]
        }';

        $arr = json_decode($json,true)['products'];
        $collection = Collection::make($arr);

        $sum = $collection->filter(
            fn($product) => in_array($product['product_type'], ['Lamp', 'Wallet'])
        )
        ->pluck('variants')
        ->map(fn($item) => array_column($item,'price') )
        ->flatten()
        ->sum();

        $this->assertEquals( 133.45 , $sum);
    }


}