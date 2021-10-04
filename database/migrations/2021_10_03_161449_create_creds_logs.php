<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCredsLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creds_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('analysis_uuid')->constrained('analyses', 'uuid');
            $table->unsignedSmallInteger('port');
            $table->string('service', 50);
            $table->string('username', 255);
            $table->string('password', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creds_logs');
    }
}
