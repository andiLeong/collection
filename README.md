

## A simple array collection

Has been use laravel collection for sometime I decided to make a simple collection wrapper for array function where you can chain array method call 

It really feels like OOP, below are some methods (mostly steal the method name from laravel but I create my own implementation) 

- map
- filter
- pluck
- unique
- only
- first
- last
- second
- sum
- average
- isEmpty
- has
- limit
- remove
- sortBy
- ...

Using multiple native php array function may not feel very oop , I think it's a good idea to wrap it, as a learning practice for me, mostly is inspired by Laravel framework

If you are looking for a robust implementation for collection please look for  https://github.com/illuminate/collections



## Usage

let say we have list of users, we want to get all subscribed users order by latest date and give me the latest 3 records and capitalize the username


```php

use Andileong\Collection\Collection;

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

//we can do as below chaining call

$collection = Collection::make($array);
$collection = $collection
    ->filter( fn($user) => $user['is_subscribed'] )
    ->map( function ($user) {
        $user['name'] = strtoupper($user['name']);
        return $user;
    })
    ->sortBy('created_at', SORT_DESC)
    ->limit(3);

```





