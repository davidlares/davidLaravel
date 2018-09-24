<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Category;
use App\Product;
use App\Transaction;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // disabling FK inconsistencies
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // truncating tables
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        // flushing event listener (App Service Providers actions)
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();

        // inserting database
        $users = 1000;
        $categories = 30;
        $products = 1000;
        $transactions = 1000;

        factory(User::class, $users)->create();
        factory(Category::class, $categories)->create();

        factory(Product::class, $transactions)->create()->each(
            function($product){
              $category = Category::all()->random(mt_rand(1,5))->pluck('id');
              $product->categories()->attach($category);
            }
        );

        factory(Transaction::class, $transactions)->create();
    }
}
