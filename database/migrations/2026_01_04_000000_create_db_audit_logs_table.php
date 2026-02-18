<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('user_audit_logs', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id')->nullable()->index();
        $table->string('user_session_id')->nullable()->index();

        $table->string('event_type'); // url_hit | created | updated | deleted
        $table->string('method')->nullable(); // GET, POST etc
        $table->text('url')->nullable();

        $table->string('connection')->nullable();
        $table->enum('operation', ['insert', 'update', 'delete']);

        $table->string('table_name')->nullable();

        $table->string('model_type')->nullable();
        $table->unsignedBigInteger('model_id')->nullable();

        $table->json('payload')->nullable();        // request data
        $table->json('before')->nullable();         // old values
        $table->json('after')->nullable();          // new values

        $table->ipAddress('ip_address')->nullable();
        $table->text('user_agent')->nullable();

        $table->text('sql')->nullable();
        $table->json('bindings')->nullable();

        $table->timestamp('executed_at');

        $table->timestamps();


        
    });



    }

    public function down()
    {
        Schema::dropIfExists('user_audit_logs');
    }
}
