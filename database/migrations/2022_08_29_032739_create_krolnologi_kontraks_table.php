<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKrolnologiKontraksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('krolnologi_kontrak', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id', 5)->nullable()->default('NULL');
            $table->date('tarikh')->nullable()->default('NULL');
            $table->string('keterangan')->nullable()->default('NULL');
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
        Schema::dropIfExists('krolnologi_kontraks');
    }
}
