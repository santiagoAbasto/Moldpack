<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordEncryptedToClientes extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->text('password_encrypted')->nullable()->after('password');
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('password_encrypted');
        });
    }
}
