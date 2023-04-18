<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors_staff', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150)->nullable()->default('NULL');
            $table->string('nokp', 20)->nullable()->default('NULL');
            $table->string('jawatan', 100)->nullable()->default('NULL');
            $table->string('emel', 100)->nullable()->default('NULL');
            $table->string('notel', 30)->nullable()->default('NULL');
            $table->integer('vendor_id', 5)->nullable()->default('NULL');
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
        Schema::dropIfExists('vendors_staff');
    }
}
