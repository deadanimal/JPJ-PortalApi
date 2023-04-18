<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKumpulanFasasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kumpulan_fasas', function (Blueprint $table) {
            $table->id();
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('fasa_projek_id', 20)->unsigned();
            $table->string('tajuk_kumpulan');
            $table->date('tarikh_mula_rancang')->nullable()->default('NULL');
            $table->date('tarikh_tamat_rancang')->nullable()->default('NULL');
            $table->date('tarikh_mula_sebenar')->nullable()->default('NULL');
            $table->date('tarikh_tamat_sebenar')->nullable()->default('NULL');
            $table->double('peratus_perancang', 10, 2)->nullable()->default('NULL');
            $table->double('peratus_sebenar', 10, 2)->nullable()->default('NULL');
            $table->string('status')->nullable()->default('NULL');
            $table->string('catatan')->nullable()->default('NULL');
            $table->timestamp('deleted_at')->nullable()->default('NULL');
            $table->timestamps();
            $table->foreign('fasa_projek_id')->references('id')->on('status_kemajuan_kerja_projeks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kumpulan_fasas');
    }
}
