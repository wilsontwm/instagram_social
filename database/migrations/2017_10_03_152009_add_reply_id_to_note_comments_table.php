<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReplyIdToNoteCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('note_comments', function(Blueprint $table){
            $table->unsignedInteger('reply_id')->after('user_id')->nullable();
            $table->foreign('reply_id')->references('id')->on('note_comments')->onDelete('set null');

            $table->unsignedInteger('reply_user_id')->after('user_id')->nullable();
            $table->foreign('reply_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note_comments', function(Blueprint $table){
            $table->dropForeign('note_comments_reply_id_foreign');
            $table->dropColumn('reply_id');

            $table->dropForeign('note_comments_reply_user_id_foreign');
            $table->dropColumn('reply_user_id');
        });
    }
}
