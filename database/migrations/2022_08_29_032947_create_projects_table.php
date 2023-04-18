<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('tajuk_projek');
            $table->string('pemilik_projek');
            $table->string('rujukan_kontrak');
            $table->bigInteger('vendor_id',20)->unsigned();
            $table->double('kos_projek_sebelum_sst', 20,2)->default('0.00');
            $table->double('kos_projek_selepas_sst', 20,2)->default('0.00');
            $table->double('bon_pelaksanaan_projek', 20,2);
            $table->string('tempoh_sah_bon')->default('');
            $table->date('tempoh_mula_kontrak');
            $table->date('tempoh_tamat_kontrak');
            $table->string('skop_projek');
            $table->string('status')->default('aktif');
            $table->tinyInteger('publish',1)->default('1');
            $table->longText('description')->collate();
            $table->timestamp('deleted_at')->nullable()->default('NULL');
            $table->timestamps();
            $table->string('pengurus_projek',50)->nullable()->default('NULL');
            $table->date('tarikh_sst')->nullable()->default('NULL');
            $table->integer('status_kontrak',5)->nullable()->default('NULL');
            $table->date('tarikh_kontrak')->nullable()->default('NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
