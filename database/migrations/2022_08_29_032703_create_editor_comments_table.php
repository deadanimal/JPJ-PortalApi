<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditorCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editor_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id', 20)->unsigned()->nullable()->default('NULL');
            $table->bigInteger('project_id', 20)->unsigned();
            $table->string('fasa_sekarang');
            $table->integer('peratus_siap', 11);
            $table->string('ulasan');
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
        Schema::dropIfExists('editor_comments');
    }
}
