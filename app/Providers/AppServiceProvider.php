<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Product;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // product event
        Product::updated(function($product){
          if($product->quantity == 0 && $product->isAvailable()){
            $product->status = Product::NOT_AVAILABLE;
            $product->save();
          }
        });

        // user event
        User::created(function($user) {
          // handling attempts with retry
            retry(5, function() use ($user) {
              Mail::to($user->email)->send(new UserCreated($user));
            }, 100);
        });

        // user event
        User::updated(function($user) {
          if($user->isDirty('email')){
            // if user changed email, sent email
            retry(5, function() use ($user) {
              Mail::to($user->email)->send(new UserMailChanged($user));
            }, 100);
          }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
