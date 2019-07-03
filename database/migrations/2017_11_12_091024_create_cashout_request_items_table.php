<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashoutRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashout_request_items', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('cashout_request_id');
            $table->foreign('cashout_request_id')->references('id')->on('cashout_requests')->onDelete('cascade');
            $table->unsignedInteger('gift_id');
            $table->foreign('gift_id')->references('id')->on('gifts')->onDelete('cascade');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashout_request_items');
    }
}
