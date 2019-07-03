<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
            $table->string('username')->index()->unique()->after('id');
            $table->string('instagram_id')->index()->after('id');
            $table->string('user_pic')->nullable()->after('password');
        });

        Schema::create('social_accounts', function(Blueprint $table){
            $table->integer('user_id')->nullable()->unsigned();
            $table->string('provider_user_id');
            $table->string('provider');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('username');
            $table->dropColumn('instagram_id');
            $table->dropColumn('user_pic');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->after('name');
        });

        Schema::drop('social_accounts');
    }
}
