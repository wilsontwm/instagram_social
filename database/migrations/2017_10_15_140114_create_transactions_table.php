<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('user_gift_id')->unsigned()->nullable();
            $table->foreign('user_gift_id')->references('id')->on('user_gifts')->onDelete('set null');
            $table->decimal('amount');
            $table->string('description')->nullable();
            $table->boolean('is_top_up');
            $table->timestamps();
        });

        Schema::create('company_transactions', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_transaction_id')->unsigned()->nullable();
            $table->foreign('user_transaction_id')->references('id')->on('user_transactions')->onDelete('set null');
            $table->decimal('amount');
            $table->string('description')->nullable();
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
        Schema::drop('company_transactions');
        Schema::drop('user_transactions');
    }
}
