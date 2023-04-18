<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_cards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id', 20)->unsigned();
            $table->string('tajuk_besar');
            $table->longText('content')->collation();
            $table->tinyInteger('publish', 1)->default('1');
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
        Schema::dropIfExists('project_cards');
    }
}
