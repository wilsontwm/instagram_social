<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_comments', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_gift_id')->index();
            $table->foreign('user_gift_id')->references('id')->on('user_gifts')->onDelete('cascade');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('reply_id')->nullable();
            $table->foreign('reply_id')->references('id')->on('gift_comments')->onDelete('set null');
            $table->unsignedInteger('reply_user_id')->nullable();
            $table->foreign('reply_user_id')->references('id')->on('users')->onDelete('set null');
            $table->longText('comment');
            $table->boolean('is_deleted')->default(false)->comment('A flag that indicates that the comment is removed by the user. Instead of deleting the comment, maintain the comment and change the comment');
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
        Schema::drop('gift_comments');
    }
}
