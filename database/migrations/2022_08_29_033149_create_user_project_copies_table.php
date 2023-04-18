<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProjectCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_projects_copy', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id', 20)->unsigned();
            $table->bigInteger('project_id', 20)->unsigned();
            $table->string('user_role_project');
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
        Schema::dropIfExists('user_project_copies');
    }
}
