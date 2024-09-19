<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cliente');
            $table->unsignedBigInteger('servicio_id');  
            $table->unsignedBigInteger('categoria_id');           
            $table->date('fecha_reservacion');
            $table->time('hora_reservacion');
            $table->string('estado')->default('pendiente');
            $table->string('telefono_cliente')->nullable(); 
            $table->timestamps();
        
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}


