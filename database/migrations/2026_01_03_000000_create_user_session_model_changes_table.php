<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSessionModelChangesTable extends Migration
{
    public function up()
    {
        Schema::create('user_session_model_changes', function (Blueprint $table) {
        $table->id();

        $table->string('user_session_id')->nullable();
        $table->unsignedBigInteger('user_id')->index();

        $table->string('model_type')->nullable();
        $table->unsignedBigInteger('model_id')->nullable();

        $table->json('before')->nullable();
        $table->json('after')->nullable();

        $table->timestamps();
    });


    }

    public function down()
    {
        Schema::dropIfExists('user_session_model_changes');
    }
}
