<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFacebookAttributesToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->string('fb_app_id', 16)->default('')->after('password');
            $table->string('fb_app_secret', 32)->default('')->after('fb_app_id');
            $table->string('fb_accesstoken')->default('')->after('fb_app_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn(['fb_app_id', 'fb_app_secret', 'fb_accesstoken']);
        });
    }
}
