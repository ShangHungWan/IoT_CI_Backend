<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFuzzingStatusInAnalyses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analyses', function (Blueprint $table) {
            $table->string('fuzzing_status', 30);
            $table->mediumInteger('crashes_number')->default(-1);
            $table->mediumInteger('hangs_number')->default(-1);
            $table->string('function_coverage_rate', 30)->nullable();
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
            $table->dropColumn('fuzzing_status');
            $table->dropColumn('crashes_number');
            $table->dropColumn('hangs_number');
            $table->dropColumn('function_coverage_rate');
        });
    }
}
