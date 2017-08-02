<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Foundation\Application;
use TestCart\Cart;
use TestCart\Item;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('cart:add {cartId} {itemId}', function(Application $app, $cartId, $itemId) {
    $app->events->dispatch('test_cart.add', [$cartId, new Item(['id' => $itemId])]);
    $app->events->dispatch('test_cart.dump', [$cartId]);
})->describe('{cartId} {itemId}');;
Artisan::command('cart:delete {cartId} {itemId}', function(Application $app, $cartId, $itemId) {
    $app->events->dispatch('test_cart.delete', [$cartId, new Item(['id' => $itemId])]);
    $app->events->dispatch('test_cart.dump', [$cartId]);
})->describe('{cartId} {itemId}');
Artisan::command('cart:coupon {cartId} {number}', function(Application $app, $cartId, $number) {
    $app->events->dispatch('test_cart.coupon', [$cartId, $number]);
    $app->events->dispatch('test_cart.dump', [$cartId]);
})->describe('{cartId} {number}  Valid coupon numbers for example amt10 and pct15');
Artisan::command('cart:dump {cartId}', function(Application $app, $cartId) {
    $app->events->dispatch('test_cart.dump', [$cartId]);
})->describe('{cartId}');
Artisan::command('cart:flush {cartId}', function(Application $app, $cartId) {
    $app->events->dispatch('test_cart.flush', [$cartId]);
})->describe('{cartId}');
Artisan::command('cart:test {cartId}', function($cartId){
    $cart = new Cart($cartId);
    $cart->addItem(new Item(['id' => 4, 'qty' => 1, 'price' => 10]));
    $cart->persist();
    echo PHP_EOL . json_encode($cart->dump(), JSON_PRETTY_PRINT);
    $cart->increment(4, 3);
    $cart->persist();
    echo PHP_EOL . json_encode($cart->dump(), JSON_PRETTY_PRINT);
    $cart->decrement(4, 1);
    $cart->persist();
    echo PHP_EOL . json_encode($cart->dump(), JSON_PRETTY_PRINT);
    $cart->assignCoupon('pct10');
    $cart->persist();
    unset($cart);
    $cart = new Cart($cartId);
    echo PHP_EOL . json_encode($cart->dump(), JSON_PRETTY_PRINT);
    $cart->flush();
    $cart->persist();
    unset($cart);
    $cart = new Cart($cartId);
    echo PHP_EOL . json_encode($cart->dump(), JSON_PRETTY_PRINT);

})->describe('{cartId}');
