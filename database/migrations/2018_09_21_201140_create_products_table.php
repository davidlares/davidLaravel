<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Product;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('quantity')->unsigned(); // positive number
            $table->string('status')->default(Product::NOT_AVAILABLE);
            $table->string('image');
            $table->integer('seller_id')->unsigned();
            $table->timestamps();
            $table->foreign('seller_id')->references('id')->on('users');
            $table->softDeletes();
            // soft deleting
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
