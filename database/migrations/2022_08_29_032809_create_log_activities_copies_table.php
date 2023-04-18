<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogActivitiesCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_activities_copy', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id', 20)->unsigned()->nullable()->default('NULL');
            $table->string('subject')->nullable()->default('NULL');
            $table->mediumText('url')->collation();
            $table->string('method')->nullable()->default('NULL');
            $table->string('ip')->nullable()->default('NULL');
            $table->string('agent')->nullable()->default('NULL');
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
        Schema::dropIfExists('log_activities_copies');
    }
}
