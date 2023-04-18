<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id', 20)->unsigned();
            $table->string('vendor_avatar')->nullable()->default('NULL');
            $table->string('telefon')->nullable()->default('NULL');
            $table->string('faks')->nullable()->default('NULL');
            $table->string('alamat')->nullable()->default('NULL');
            $table->string('website')->nullable()->default('NULL');
            $table->timestamp('deleted_at')->nullable()->default('NULL');
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
        Schema::dropIfExists('vendor_profiles');
    }
}
