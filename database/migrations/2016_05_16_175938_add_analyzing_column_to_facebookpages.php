<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnalyzingColumnToFacebookpages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facebookpages', function(Blueprint $table) {
            $table->boolean('analyzing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facebookpages', function(Blueprint $table) {
            $table->dropColumn('analyzing');
        });
    }
}
