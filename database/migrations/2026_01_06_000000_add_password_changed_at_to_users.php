<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordChangedAtToUsers extends Migration
{
    public function up()
    {
       Schema::table('users', function (Blueprint $table) {
            $table->timestamp('password_changed_at')->nullable();
            $table->boolean('must_change_password')->default(false);
            
        });
        
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'password_changed_at',
                'must_change_password'
            ]);
        });
    }
}
