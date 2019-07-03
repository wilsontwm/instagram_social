<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_comments', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('note_id')->index();
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::drop('note_comments');
    }
}
