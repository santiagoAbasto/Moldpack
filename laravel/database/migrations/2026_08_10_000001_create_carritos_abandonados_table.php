<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarritosAbandonadosTable extends Migration
{
    public function up()
    {
        Schema::create('carritos_abandonados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id')->unique();
            $table->string('email')->nullable();
            $table->longText('items')->nullable();
            $table->unsignedInteger('items_count')->default(0);
            $table->decimal('total_estimado', 12, 2)->default(0);
            $table->timestamp('last_activity_at')->nullable()->index();
            $table->timestamp('reminder_sent_at')->nullable()->index();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carritos_abandonados');
    }
}
