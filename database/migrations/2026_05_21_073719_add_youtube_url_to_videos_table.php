<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('youtube_url')->nullable()->after('url');
            // اجعل url nullable أيضاً
            $table->string('url')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('youtube_url');
            $table->string('url')->nullable(false)->change();
        });
    }
};
