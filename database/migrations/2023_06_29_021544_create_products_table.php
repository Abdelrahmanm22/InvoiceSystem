<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // $table->string('Product_code', 99);
            $table->string('Product_name', 999);
            $table->text('description')->nullable();
            $table->string('user', 999);
            $table->integer('price');
            $table->integer('mini_price');
            $table->integer('Wholesale_Price');
            $table->integer('quantity');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            // $table->softDeletes(); //To Prevent delete product from orders
            $table->timestamps();
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
};
