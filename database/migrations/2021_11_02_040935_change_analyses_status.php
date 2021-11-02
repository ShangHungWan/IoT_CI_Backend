<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAnalysesStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analyses', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('static_status', 30);
            $table->string('dynamic_status', 30);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analyses', function (Blueprint $table) {
            $table->dropColumn('static_status');
            $table->dropColumn('dynamic_status');
            $table->string('status', 30);
        });
    }
}
