<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusKemajuanKewanganProjeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_kemajuan_kewangan_projeks', function (Blueprint $table) {
            $table->id();
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('project_id', 20)->unsigned();
            $table->integer('tahun', 11);
            $table->string('bulan');
            $table->double('nilai_kontrak', 20, 2)->nullable()->default('NULL');
            $table->double('jadual_tuntutan', 20, 2)->nullable()->default('NULL');
            $table->double('telah_dituntut', 20, 2)->nullable()->default('NULL');
            $table->double('belum_dituntut', 20, 2)->nullable()->default('NULL');
            $table->double('telah_dibayar', 20, 2)->nullable()->default('NULL');
            $table->double('belum_dibayar', 20, 2)->nullable()->default('NULL');
            $table->date('tarikh')->nullable()->default('NULL');
            $table->timestamp('deleted_at')->nullable()->default('NULL');
            $table->timestamp('created_at')->nullable()->default('NULL');
            $table->timestamp('updated_at')->nullable()->default('NULL');
            $table->primary('id');
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
        Schema::dropIfExists('status_kemajuan_kewangan_projeks');
    }
}
