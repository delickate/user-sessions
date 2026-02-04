<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('db_audit_logs', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id')->nullable()->index();
        $table->unsignedBigInteger('user_session_id')->nullable()->index();

        $table->string('connection')->nullable();
        $table->enum('operation', ['insert', 'update', 'delete']);

        $table->string('table_name')->nullable();

        $table->json('before')->nullable();
        $table->json('after')->nullable();

        $table->text('sql');
        $table->json('bindings')->nullable();

        $table->timestamp('executed_at');

        $table->timestamps();
    });



    }

    public function down()
    {
        Schema::dropIfExists('db_audit_logs');
    }
}
