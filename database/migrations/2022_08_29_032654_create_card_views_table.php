<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_views', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('card_id', 20)->unsigned();
            $table->tinyInteger('staff', 1)->default('1');
            $table->tinyInteger('editor', 1)->default('1');
            $table->tinyInteger('normal_user', 1)->default('1');
            $table->tinyInteger('guest', 1)->default('1');
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
        Schema::dropIfExists('card_views');
    }
}
