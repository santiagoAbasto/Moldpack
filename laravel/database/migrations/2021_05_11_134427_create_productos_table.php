<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('orden')->nullable();
            $table->string('nombre')->nullable();
            $table->text('imagen')->nullable();
            $table->text('galeria')->nullable();
            $table->text('plano')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('ventajas')->nullable();
            $table->string('aplicaciones')->nullable();
            $table->integer('categorias_id')->nullable();
            $table->boolean('destacado')->nullable();
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
        Schema::dropIfExists('productos');
    }
}
