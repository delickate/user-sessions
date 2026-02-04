<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSessionActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('user_session_activities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_session_id')->index();
            $table->unsignedBigInteger('user_id')->index();

            $table->string('method', 10);
            $table->string('url');
            $table->string('route_name')->nullable();

            $table->json('payload')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('hit_at');

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('user_session_activities');
    }
}
