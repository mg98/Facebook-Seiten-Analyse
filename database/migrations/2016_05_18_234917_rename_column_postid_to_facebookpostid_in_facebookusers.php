<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnPostidToFacebookpostidInFacebookusers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facebook_users', function(Blueprint $table) {
            $table->renameColumn('post_id', 'facebook_post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facebook_users', function(Blueprint $table) {
            $table->renameColumn('facebook_post_id', 'post_id');
        });
    }
}
