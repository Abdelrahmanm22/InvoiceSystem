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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number', 50);
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->date('invoice_Date')->nullable();
            // $table->date('Due_date')->nullable();
            // $table->string('product', 50);
            // $table->bigInteger( 'section_id' )->unsigned();
            // $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            // $table->decimal('Amount_collection',8,2)->nullable();
            // $table->decimal('Amount_Commission',8,2);
            // $table->decimal('Discount',8,2);
            // $table->decimal('Value_VAT',8,2);
            // $table->string('Rate_VAT', 999);
            $table->decimal('Total',10,2);
            $table->decimal('partial',10,2);
            $table->string('Status', 50);
            $table->string('client', 100)->nullable();
            $table->string('phoneClient', 50)->nullable();
            $table->integer('Value_Status');
            $table->text('note')->nullable();
            $table->date('Payment_Date')->nullable();
            $table->softDeletes(); //To archive
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
        Schema::dropIfExists('invoices');
    }
};
