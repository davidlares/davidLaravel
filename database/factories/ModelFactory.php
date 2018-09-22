<?php

use App\User;
use App\Product;
use App\Category;
use App\Seller;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** User Factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $verified = $faker->randomElement([User::VERIFIED, User::NOT_VERIFIED]),
        'verification_token' => $verified == User::VERIFIED ? null : User::generateVerificationToken(),
        'admin' => $faker->randomElement([User::ADMIN, User::REGULAR])
    ];
});

/** Category Factory **/
$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

/** Product Factory **/
$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1,10),
        'status' => $faker->randomElement([Product::AVAILABLE, Product::NOT_AVAILABLE]),
        'image' => $faker->randomElement(['1.jpg','2.jpg','3.jpg']),
        'seller_id' => User::inRandomOrder()->first()->id,
    ];
});

/** Transaction Factory **/
$factory->define(App\Transaction::class, function (Faker\Generator $faker) {

    // any user with an active sell
    // avoiding same user (buy & sell operation)
    $vendors = Seller::has('products')->get()->random();
    $buyer = User::all()->except($vendors->id)->random();

    return [
        'quantity' => $faker->numberBetween(1,10),
        'buyer_id' => $buyer->id,
        'product_id' => $vendors->products->random()->id,
    ];
});
