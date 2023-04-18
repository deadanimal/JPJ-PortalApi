<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusKemajuanKerjaProjeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_kemajuan_kerja_projeks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id', 20)->unsigned();
            $table->string('fasa_projek');
            $table->timestamp('deleted_at')->nullable()->default('NULL');
            $table->timestamps();
            $table->double('peratusan_pemberat', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_kemajuan_kerja_projeks');
    }
}
