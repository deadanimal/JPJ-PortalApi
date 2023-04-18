<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBahagiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bahagian', function (Blueprint $table) {
            $table->id();
            $table->string('kod', 60)->nullable()->default('NULL');
            $table->string('bahagian', 300)->nullable()->default('NULL');
            $table->string('keterangan', 300)->nullable()->default('NULL');
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
        Schema::dropIfExists('bahagians');
    }
}
