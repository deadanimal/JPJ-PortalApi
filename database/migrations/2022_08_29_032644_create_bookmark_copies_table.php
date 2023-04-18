<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarkCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks_copy', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id', 20)->unsigned();
            $table->bigInteger('project_id', 20)->unsigned();
            $table->tinyInteger('show_hide', 1)->default('1');
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
        Schema::dropIfExists('bookmark_copies');
    }
}
