<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExceptionLogsTable extends Migration
{
    public function up()
    {
       Schema::create('exception_logs', function (Blueprint $table) {
        $table->id();
        $table->text('message');
        $table->text('file')->nullable();
        $table->integer('line')->nullable();
        $table->longText('trace')->nullable();
        $table->string('url')->nullable();
        $table->string('method')->nullable();
        $table->ipAddress('ip')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->timestamps();
        
    }

    public function down()
    {
        Schema::dropIfExists('exception_logs');
    }
}
